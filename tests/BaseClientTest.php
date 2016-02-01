<?php

namespace xutongle\extensions\apistore;

use xutongle\apistore\BaseClient;

class BaseClientTest extends TestCase
{
    public function testSetGet()
    {
        $client = new Client();

        $id = 'test_id';
        $client->setId($id);
        $this->assertEquals($id, $client->getId(), 'Unable to setup id!');

        $name = 'test_name';
        $client->setName($name);
        $this->assertEquals($name, $client->getName(), 'Unable to setup name!');

    }

    public function testGetDefaults()
    {
        $client = new Client();

        $this->assertNotEmpty($client->getName(), 'Unable to get default name!');
    }

    
}

class Client extends BaseClient
{
}
