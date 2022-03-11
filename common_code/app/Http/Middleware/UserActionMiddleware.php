<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\UserAction;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Auth;
use Session;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;

class UserActionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        try{
            //saving the page views
            $vview = new UserAction();
            $vview->user = (Auth::check() ? Auth::user()->id : null);
            $vview->ip = $request->ip;
    
            $uid = "";
    
            if(Session::has("PLACEMENTS-UID"))
                $uid = Session::get("PLACEMENTS-UID");
            else
            {
                $uid = (string) Str::orderedUuid();
                Session::put("PLACEMENTS-UID", $uid);
            }
            $vview->uid = $uid;

            if(Auth::check() and Auth::user()->type == "Internal User"){
                $vview->action_type = "INTERNAL_PAGE_VIEW";
            }
            else{
                $vview->action_type = "PAGE_VIEW";
            }

            $vview->page_url = $request->getRequestUri();
            $vview->page_base_url = strtok($request->getRequestUri(), '?');
            $vview->source_url = url()->previous();

            //determining the source
            if(!empty($request->fbclid)) //Facebook
            {
                $vview->source = "FACEBOOK";
                $vview->source_id = $request->fbclid;
            }
            else if($request->c == "email" or !empty($request->claim)) //Email 
            {
                //https://placements.lk/company/signup?c=email&n=companyxyz
                $vview->source = "EMAIL";
                $vview->source_id = $request->n;
            }
            else if(!empty($request->claim)) //Email including the company claim requests
            {
                //https://placements.lk/company/signup?c=email&n=companyxyz
                $vview->source = "EMAIL-CLAIM";
                $vview->source_id = $request->claim;

                try{
                    if(!empty($request->claim))
                    {
                        $vview->source_id = Crypt::decryptString($claim);
                    }
                }catch(\Exception $e) {}
            }
            else if(strpos($vview->source_url, "placements.lk") >= 0)
            {
                $vview->source = "PLACEMENTS";
                $vview->source_id = "";
            }
            else if(strpos($vview->source_url, "google") >= 0)
            {
                $vview->source = "GOOGLE";
                $vview->source_id = "";
            }
            else if(strpos($vview->source_url, "linkedin") >= 0)
            {
                $vview->source = "LINKEDIN";
                $vview->source_id = "";
            }
            else if(!empty($request->c)) //Any manual Source
            {
                $vview->source = $request->c;
                $vview->source_id = $request->n;
            }
            else
            {
                $vview->source = "OTHER";
                $vview->source_id = "";
            }

            $vview->save();
        } catch(\Exception $e) {
        }

        return $next($request);
    }
}
