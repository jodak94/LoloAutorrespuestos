<?php

namespace Modules\Ventas\Events\Handlers;

use Maatwebsite\Sidebar\Group;
use Maatwebsite\Sidebar\Item;
use Maatwebsite\Sidebar\Menu;
use Modules\Core\Events\BuildingSidebar;
use Modules\User\Contracts\Authentication;

class RegisterVentasSidebar implements \Maatwebsite\Sidebar\SidebarExtender
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
            $group->item(trans('ventas::ventas.title.ventas'), function (Item $item) {
                $item->icon('fa fa-copy');
                $item->weight(10);
                $item->authorize(
                     /* append */
                );
                $item->item(trans('ventas::ventas.title.ventas'), function (Item $item) {
                    $item->icon('fa fa-copy');
                    $item->weight(0);
                    $item->append('admin.ventas.venta.create');
                    $item->route('admin.ventas.venta.index');
                    $item->authorize(
                        $this->auth->hasAccess('ventas.ventas.index')
                    );
                });
                $item->item(trans('ventas::ventadetalles.title.ventadetalles'), function (Item $item) {
                    $item->icon('fa fa-copy');
                    $item->weight(0);
                    $item->append('admin.ventas.ventadetalle.create');
                    $item->route('admin.ventas.ventadetalle.index');
                    $item->authorize(
                        $this->auth->hasAccess('ventas.ventadetalles.index')
                    );
                });
// append


            });
        });

        return $menu;
    }
}
