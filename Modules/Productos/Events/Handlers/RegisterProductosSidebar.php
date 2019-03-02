<?php

namespace Modules\Productos\Events\Handlers;

use Maatwebsite\Sidebar\Group;
use Maatwebsite\Sidebar\Item;
use Maatwebsite\Sidebar\Menu;
use Modules\Core\Events\BuildingSidebar;
use Modules\User\Contracts\Authentication;

class RegisterProductosSidebar implements \Maatwebsite\Sidebar\SidebarExtender
{
    /**
     * @var Authentication
     */
    protected $auth;

    /**
     * @param Authentication $auth
     *
     * @internal param Guard $guard
     */
    public function __construct(Authentication $auth)
    {
        $this->auth = $auth;
    }

    public function handle(BuildingSidebar $sidebar)
    {
        $sidebar->add($this->extendWith($sidebar->getMenu()));
    }

    /**
     * @param Menu $menu
     * @return Menu
     */
    public function extendWith(Menu $menu)
    {
        $menu->group(trans('core::sidebar.content'), function (Group $group) {
            $group->item(trans('productos::productos.title.productos'), function (Item $item) {
                $item->icon('fa fa-tags');
                $item->weight(10);
                $item->authorize(
                     /* append */
                );
                $item->item(trans('productos::productos.title.productos'), function (Item $item) {
                    $item->icon('fa fa-tags');
                    $item->weight(0);
                    $item->append('admin.productos.producto.create');
                    $item->route('admin.productos.producto.index');
                    $item->authorize(
                        $this->auth->hasAccess('productos.productos.index')
                    );
                });
// append       
                $item->item('Entrada de Productos', function (Item $item) {
                    $item->icon('fa fa-tags');
                    $item->weight(0);
                    $item->route('admin.productos.producto.entrada');
                });

            });
        });

        return $menu;
    }
}
