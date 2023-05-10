<?php
declare(strict_types=1);

namespace Tests\Unit;

use MyApp\Controllers\IndexController;
use MyApp\Models\Users;

class IndexControllerTest extends AbstractUnitTest
//class UnitTest extends \PHPUnit\Framework\TestCase
{

 
    public function testUsers()
    {
        $model = new Users();
        $model->name='a';
        $model->email="@gmail.com";
        $model->password="123";
        $res=$model->save();
        $this->assertEquals($res, 1);
    }
}