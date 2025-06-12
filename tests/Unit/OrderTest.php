<?php
namespace Tests\Unit;

use App\Models\User\User;
use App\Services\User\AddressServices;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use PHPUnit\Framework\TestCase;

class OrderTest extends TestCase
{

    use DatabaseTransactions;

    public function testSubmit()
    {
        $this->user = User::factory()->defaultAddress()->create();
        $address    = AddressServices::getInstance()->getDefaultAddress($this->user->id);
    }
}