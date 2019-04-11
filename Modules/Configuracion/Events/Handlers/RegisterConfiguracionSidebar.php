<?php

namespace Modules\Configuracion\Events\Handlers;

use Maatwebsite\Sidebar\Group;
use Maatwebsite\Sidebar\Item;
use Maatwebsite\Sidebar\Menu;
use Modules\Core\Events\BuildingSidebar;
use Modules\User\Contracts\Authentication;

class RegisterConfiguracionSidebar implements \Maatwebsite\Sidebar\SidebarExtender
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
            $group->item('Configuración Admin', function (Item $item) {
                $item->icon('fa fa-cog');
                $item->weight(12);
                $item->route('admin.configuracion.configuracion.index');
                $item->authorize(
                     $this->auth->hasAccess('configuracion.configuracions.index')
                );
            });
            $group->item('Configuración', function (Item $item) {
                $item->icon('fa fa-cog');
                $item->weight(11);
                $item->route('admin.configuracion.configuracion.configurar');
                $item->authorize(
                     //$this->auth->hasAccess('configuracion.configuracions.index')
                );
            });
        });

        return $menu;
    }
}
