    <?php

    namespace App\Http\Middleware;

use App\Http\Traits\ApiResponseTrait;
use Closure;
    use JWTAuth;
    use Exception;
use Illuminate\Support\Facades\Auth;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth as FacadesJWTAuth;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

    class JwtMiddleware extends BaseMiddleware
    {
        use ApiResponseTrait;
        /**
         * Handle an incoming request.
         *
         * @param  \Illuminate\Http\Request  $request
         * @param  \Closure  $next
         * @return mixed
         */
        public function handle($request, Closure $next)
        {
        if(Auth::check()){
            return $next($request);
            }
            else{
                $this->errorResponse('يرجي ارسال الtoken',null,403);
            }
        }
    }
