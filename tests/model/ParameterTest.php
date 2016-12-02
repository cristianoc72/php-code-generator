<?php
namespace gossi\codegen\tests\model;

use gossi\codegen\model\PhpFunction;
use gossi\codegen\model\PhpParameter;
use gossi\codegen\tests\parts\ValueTests;

/**
 * @group model
 */
class ParameterTest extends \PHPUnit_Framework_TestCase {

	use ValueTests;

	public function testByReference() {
		$param = new PhpParameter();
		$this->assertFalse($param->isPassedByReference());
		$param->setPassedByReference(true);
		$this->assertTrue($param->isPassedByReference());
		$param->setPassedByReference(false);
		$this->assertFalse($param->isPassedByReference());
	}

	public function testType() {
		$param = new PhpParameter();

		$this->assertNull($param->getType());
		$this->assertSame($param, $param->setType('array'));
		$this->assertEquals('array', $param->getType());
		$this->assertSame($param, $param->setType('array', 'boo!'));
		$this->assertEquals('boo!', $param->getTypeDescription());
	}

	public function testSimpleParameter() {
		$function = new PhpFunction();
		$function->addSimpleParameter('param1', 'string');

		$this->assertTrue($function->hasParameter('param1'));
		$this->assertFalse($function->hasParameter('param2'));
		$param1 = $function->getParameter('param1');
		$this->assertEquals('string', $param1->getType());
		$this->assertFalse($param1->hasValue());

		$function->addSimpleParameter('param2', 'string', null);

		$this->assertTrue($function->hasParameter('param2'));
		$param2 = $function->getParameter('param2');
		$this->assertEquals('string', $param2->getType());
		$this->assertNull($param2->getValue());
	}

	public function testSimpleParameterWithArrayDefaultValue()
    {
    	$function = new PhpFunction();
		$function->addSimpleParameter('param1', 'array', array('foo' => 'bar', 'foobaz' => 'barbaz'));

		$this->assertTrue($function->hasParameter('param1'));
		$param1 = $function->getParameter('param1');
		$this->assertEquals('array', $param1->getType());
		$this->assertTrue($param1->hasValue());
		$this->assertTrue(is_string($param1->getExpression()));
		$this->assertEquals('[\'foo\' => \'bar\', \'foobaz\' => \'barbaz\']', $param1->getExpression());
	}

	public function testSimpleDescParameter() {
		$function = new PhpFunction();
		$function->addSimpleDescParameter('param1', 'string');

		$this->assertFalse($function->hasParameter('param2'));
		$param1 = $function->getParameter('param1');
		$this->assertEquals('string', $param1->getType());
		$this->assertFalse($param1->hasValue());

		$function->addSimpleDescParameter('param2', 'string', 'desc');

		$this->assertTrue($function->hasParameter('param2'));
		$param2 = $function->getParameter('param2');
		$this->assertEquals('string', $param2->getType());
		$this->assertFalse($param2->hasValue());

		$function->addSimpleDescParameter('param3', 'string', 'desc', null);

		$this->assertTrue($function->hasParameter('param3'));
		$param3 = $function->getParameter('param3');
		$this->assertEquals('string', $param3->getType());
		$this->assertNull($param3->getValue());
	}

	public function testSimpleDescParameterWithArrayDefaultValue()
	{
		$function = new PhpFunction();
		$function->addSimpleDescParameter('param1', 'array', 'desc', array(1, 2, 3));

		$this->assertTrue($function->hasParameter('param1'));
		$param1 = $function->getParameter('param1');
		$this->assertEquals('array', $param1->getType());
		$this->assertEquals('desc', $param1->getDescription());
		$this->assertTrue($param1->hasValue());
		$this->assertTrue(is_string($param1->getExpression()));
		$this->assertEquals('[1, 2, 3]', $param1->getExpression());
	}

	public function testValues() {
		$this->isValueString(PhpParameter::create()->setValue('hello'));
		$this->isValueInteger(PhpParameter::create()->setValue(2));
		$this->isValueFloat(PhpParameter::create()->setValue(0.2));
		$this->isValueBool(PhpParameter::create()->setValue(false));
		$this->isValueNull(PhpParameter::create()->setValue(null));
	}

}
