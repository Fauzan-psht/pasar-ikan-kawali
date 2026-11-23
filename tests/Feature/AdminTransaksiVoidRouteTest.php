<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class AdminTransaksiVoidRouteTest extends TestCase
{
    public function test_admin_transaction_void_route_is_registered_for_post_requests(): void
    {
        $route = Route::getRoutes()->getByName('admin.transaksi.void');

        $this->assertNotNull($route);
        $this->assertContains('POST', $route->methods());
        $this->assertSame('admin/transaksi/{transaksi}/void', $route->uri());
        $this->assertSame('/admin/transaksi/123/void', parse_url(route('admin.transaksi.void', 123), PHP_URL_PATH));
    }
}
