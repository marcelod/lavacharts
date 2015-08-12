<?php

namespace Khill\Lavacharts\Tests\Dashboards;

use \Khill\Lavacharts\Tests\ProvidersTestCase;
use \Khill\Lavacharts\Dashboards\ControlWrapper;
use \Mockery as m;

class ControlWrapperTest extends ProvidersTestCase
{
    public $ControlWrapper;
    public $mockElementId;
    public $jsonOutput;

    public function setUp()
    {
        parent::setUp();

        $this->mockElementId = m::mock('\Khill\Lavacharts\Values\ElementId', ['TestId'])->makePartial();
        $this->jsonOutput = '{"controlType":"NumberRangeFilter","containerId":"TestId","options":{"Option1":5,"Option2":true}}';

        $mockNumberFilter = m::mock('\Khill\Lavacharts\Dashboards\Filters\NumberRange')
            ->shouldReceive('getType')
            ->once()
            ->andReturn('NumberRangeFilter')
            ->shouldReceive('jsonSerialize')
            ->once()
            ->andReturn([
                'Option1' => 5,
                'Option2' => true
            ])
            ->getMock();

        $this->ControlWrapper = new ControlWrapper($mockNumberFilter, $this->mockElementId);
    }

    public function testJsonSerializationOutput()
    {
        $this->assertEquals($this->jsonOutput, json_encode($this->ControlWrapper));
    }

    public function testToJavascriptOutput()
    {
        $javascript = 'new google.visualization.ControlWrapper('.$this->jsonOutput.')';

        $this->assertEquals($javascript, $this->ControlWrapper->toJavascript());
    }
}
