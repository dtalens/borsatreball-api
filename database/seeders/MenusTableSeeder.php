<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menu;
use Illuminate\Support\Facades\File;

class MenusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $json = File::get(__DIR__ . '/menu.json');
        $data = json_decode($json);

        foreach ($data as $item){
            Menu::create(array(
                'id' => $item->id,
                'order' => $item->order,
                'icon' => $item->icon,
                'text' => $item->text,
                'path' => $item->path,
                'rol' => $item->rol,
                'parent' => $item->parent,
                'model' => $item->model,
                'active' => $item->active,
                'comments' => $item->comments,
                'icon_alt' => $item->icon_alt,
            ));

        }
    }
}
