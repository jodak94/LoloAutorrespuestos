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
                $item->icon('fa fa-plus-square');
                $item->weight(1);
                $item->authorize(
                     /* append */
                );
                $item->item(trans('Ventas'), function (Item $item) {
                    $item->icon('fa fa-cart-plus');
                    $item->weight(0);
                    $item->append('admin.ventas.venta.create');
                    $item->route('admin.ventas.venta.index');
                    $item->authorize(
                        $this->auth->hasAccess('ventas.ventas.index')
                    );
                });
                $item->item('Ventas a crédito', function (Item $item) {
                    $item->icon('fa fa-credit-card');
                    $item->weight(0);
                    $item->route('admin.ventas.venta.index', ['credito']);
                    $item->authorize(
                        $this->auth->hasAccess('ventas.ventas.index')
                    );
                });
                $item->item(trans('Ventas Parciales'), function (Item $item) {
                    $item->icon('fa fa-shopping-cart');
                    $item->weight(0);
                    $item->append('admin.ventas.venta.create_parcial');
                    $item->route('admin.ventas.venta.index', ['parcial']);
                    $item->authorize(
                        $this->auth->hasAccess('ventas.ventas.index')
                    );
                });
            });
        });

        return $menu;
    }
}
