<?php
namespace Tests\Feature;

use App\Models\User\Address;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class AddressTest extends TestCase
{
    use DatabaseTransactions;

    public function testList()
    {
        $authHeader = $this->getAuthHeader();
        $response   = $this->getJson('wx/address/list', $authHeader);

        $response->assertJson(['errno' => 0]);
    }

    public function testDelete()
    {
        $authHeader = $this->getAuthHeader();
        $response   = $this->getJson('wx/address/list', $authHeader);
        $address    = $response->getOriginalContent()['data']['list'][0];
        $this->assertNotEmpty(actual: $address);
        $response = $this->post('wx/address/delete', ['id' => $address['id']], $this->getAuthHeader());
        $response->assertJson(['errno' => 0]);
        $address = Address::query()->find($address['id']);
        $this->assertEmpty($address);
    }

}