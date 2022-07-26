<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\DynamoDb\OssProdDeclineUser;
use App\Models\DynamoDb\OssProdDuplicateUser;
use App\Models\DynamoDb\Offer;
use Tests\TestCase;

class DynamoDbTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testPersistAndRetrieve()
    {

        $offer = new Offer();
        $offer->hash = '11';
        $offer->co_hash = '123456789010';
        $offer->timestamps = true;
        $offer->save();


        $returnedOfferObject = $offer->find(['id' => '11', 'co_hash' => '123456789010']);

        $this->assertEquals([$offer->hash, $offer->co_hash], [$returnedOfferObject->hash,$returnedOfferObject->co_hash]);

        $declineUser = new OssProdDeclineUser();
        $declineUser->hash = '22';
        $declineUser->co_hash = '1234567890101112';
        $declineUser->timestamps = true;
        $declineUser->save();

        $returnedOfferObject = $declineUser->find(['id' => '22', 'co_hash' => '1234567890101112']);

        $this->assertEquals([$declineUser->hash, $declineUser->co_hash], [$returnedOfferObject->hash,$returnedOfferObject->co_hash] );

        $duplicateUser = new OssProdDuplicateUser();
        $duplicateUser->hash = '33';
        $duplicateUser->co_hash = '123456789010111213';
        $duplicateUser->timestamps = true;
        $duplicateUser->save();

        $returnedOfferObject = $duplicateUser->find(['id' => '33', 'co_hash' => '123456789010111213']);

        $this->assertEquals([$duplicateUser->hash, $duplicateUser->co_hash], [$returnedOfferObject->hash,$returnedOfferObject->co_hash] );

    }
}
