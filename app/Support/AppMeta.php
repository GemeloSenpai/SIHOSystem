<?php 

// app/Support/AppMeta.php
namespace App\Support;
use Illuminate\Support\Facades\DB;

class AppMeta {
    public static function get(string $key, $default=null) {
        $row = DB::table('app_meta')->where('clave',$key)->first();
        return $row? $row->valor : $default;
    }
    public static function set(string $key, $value): void {
        $exists = DB::table('app_meta')->where('clave',$key)->exists();
        if ($exists) {
            DB::table('app_meta')->where('clave',$key)->update(['valor'=>$value,'updated_at'=>now()]);
        } else {
            DB::table('app_meta')->insert(['clave'=>$key,'valor'=>$value,'created_at'=>now(),'updated_at'=>now()]);
        }
    }
}
