<?php
// Isolated temporary SQLite DB; never touches the user's configured MySQL DB.
if (is_file(__DIR__.'/../bootstrap/cache/config.php')) { fwrite(STDERR, "Run php artisan config:clear before testing.\n"); exit(1); }
require __DIR__.'/../vendor/autoload.php';
$file=tempnam(sys_get_temp_dir(),'eshopper_test_');
foreach(['DB_CONNECTION'=>'sqlite','DB_DATABASE'=>$file,'APP_ENV'=>'testing','APP_KEY'=>'base64:'.base64_encode(random_bytes(32)),'CACHE_STORE'=>'array','SESSION_DRIVER'=>'array'] as $k=>$v){putenv("$k=$v");$_ENV[$k]=$v;$_SERVER[$k]=$v;}
$app=require __DIR__.'/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use App\Models\{User,Category,Product,ProductVariant,CartItem,Order};
use App\Services\OrderService;
$checks=0;
function check($condition,$message){global $checks;if(!$condition)throw new RuntimeException($message);$checks++;echo "PASS: $message\n";}
function rejected($fn,$message){try{$fn();}catch(ValidationException $e){check(true,$message);return;}throw new RuntimeException('Expected validation error: '.$message);}
try {
 Artisan::call('migrate',['--force'=>true]);Artisan::call('db:seed',['--force'=>true]);
 check(User::count()===3&&Product::count()===15&&ProductVariant::count()===30,'Seeder creates users/products/variants');
 Artisan::call('db:seed',['--force'=>true]);check(Product::count()===15,'Seed rerun preserves data');
 check(Auth::attempt(['email'=>'usera@example.test','password'=>'DemoShop2026!']),'Demo password authenticates');
 $a=User::where('email','usera@example.test')->firstOrFail();$b=User::where('email','userb@example.test')->firstOrFail();$admin=User::where('role','admin')->firstOrFail();$service=app(OrderService::class);
 $quote=[];foreach(CartItem::where('user_id',$a->id)->with('variant')->get() as $i)$quote[$i->variant_id]=['price'=>$i->variant->price,'quantity'=>$i->quantity];
 $stocks=ProductVariant::whereIn('id',array_keys($quote))->pluck('stock','id');$token=(string)Str::uuid();$delivery=['recipient_name'=>'Khách A','phone'=>'0900000000','address'=>'Địa chỉ test'];
 $o=$service->checkout($a,$delivery,$token,$quote);
 check($o->items()->count()===2 && $o->total_amount===320000,'Checkout stores both variants and correct total');
 check(CartItem::where('user_id',$a->id)->count()===0 && CartItem::where('user_id',$b->id)->count()===2,'Checkout clears only owner cart');
 foreach($quote as $id=>$q)check(ProductVariant::find($id)->stock===$stocks[$id]-$q['quantity'],'Checkout decrements variant stock');
 $again=$service->checkout($a,$delivery,$token,$quote);check($again->id===$o->id&&Order::where('checkout_token',$token)->count()===1,'Repeated token returns same order');
 try{$service->transition($b,$o,'cancelled');throw new RuntimeException('Owner protection failed');}catch(\Symfony\Component\HttpKernel\Exception\HttpException $e){check($e->getStatusCode()===403,'Other user cannot cancel order');}
 $service->transition($a,$o,'cancelled');$service->transition($a,$o,'cancelled');foreach($quote as $id=>$q)check(ProductVariant::find($id)->stock===$stocks[$id],'Cancellation restores stock only once');
 $p=ProductVariant::where('sku','DEMO-05-M')->firstOrFail();CartItem::create(['user_id'=>$a->id,'variant_id'=>$p->id,'quantity'=>1]);$count=Order::count();
 rejected(fn()=>$service->checkout($a,$delivery,(string)Str::uuid(),[$p->id=>['price'=>$p->price-1,'quantity'=>1]]),'Stale price rejected');check(Order::count()===$count&&CartItem::where('user_id',$a->id)->count()===1,'Failure leaves order/cart intact');
 $p->update(['stock'=>0]);rejected(fn()=>$service->checkout($a,$delivery,(string)Str::uuid(),[$p->id=>['price'=>$p->price,'quantity'=>1]]),'Out of stock rejected');$p->update(['stock'=>20]);
 $q=[$p->id=>['price'=>$p->price,'quantity'=>1]];$next=$service->checkout($a,$delivery,(string)Str::uuid(),$q);
 rejected(fn()=>$service->transition($admin,$next,'completed'),'Cannot skip status transitions');$service->transition($admin,$next,'confirmed');$service->transition($admin,$next,'shipping');$service->transition($admin,$next,'completed');check($next->fresh()->status==='completed','Admin advances through allowed statuses');
 Artisan::call('view:cache');check(true,'All Blade templates compile');
 $base=['navCategories'=>Category::all(),'cartCount'=>0];
 $pages=['home'=>['products'=>Product::with('variants')->take(8)->get()],'shop'=>['products'=>Product::with('variants')->paginate(12),'categories'=>Category::all(),'sizes'=>['M','L'],'colors'=>['Đen','Trắng']],'detail'=>['product'=>Product::with('variants','reviews','category')->findOrFail(3)],'auth'=>['register'=>false],'cart'=>['items'=>CartItem::where('user_id',$b->id)->with('variant.product')->get()],'checkout'=>['items'=>CartItem::where('user_id',$b->id)->with('variant.product')->get(),'token'=>(string)Str::uuid()],'dashboard'=>['productCount'=>15,'orderCount'=>Order::count(),'pendingCount'=>2,'revenue'=>0],'categories'=>['categories'=>Category::withCount('products')->paginate(15)],'category-form'=>['category'=>new Category],'products'=>['products'=>Product::with('category','variants')->paginate(15)],'product-form'=>['product'=>Product::with('variants')->findOrFail(3),'categories'=>Category::all()],'orders'=>['orders'=>Order::paginate(15),'admin'=>false],'order-detail'=>['order'=>$o->load('items'),'admin'=>false],'report'=>[],'showrooms'=>['showrooms'=>json_decode(file_get_contents(resource_path('data/showrooms.json')))]];
 foreach($pages as $page=>$data){$html=view($page,array_merge($base,$data,['errors'=>new \Illuminate\Support\ViewErrorBag]))->render();check(str_contains($html,'</html>'),"Render $page");}
 echo "SUCCESS: $checks checks passed.\n";
} finally {
 \Illuminate\Support\Facades\DB::disconnect();@unlink($file);Artisan::call('view:clear');
}
