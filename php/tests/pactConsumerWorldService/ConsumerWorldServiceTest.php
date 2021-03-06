<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

use PhpPact\Consumer\InteractionBuilder;
use PhpPact\Consumer\Matcher\Matcher;
use PhpPact\Consumer\Model\ConsumerRequest;
use PhpPact\Consumer\Model\ProviderResponse;
use PhpPact\Standalone\MockService\MockServerEnvConfig;


final class ConsumerWorldServiceTest extends TestCase
{
    
    public function setUp(): void {
        parent::setUp();
        putenv('HELLO_SERVICE_URL=localhost:7200');
        putenv('WORLD_SERVICE_URL=localhost:7200');
    }
    
    public function testGetWorldString(): void
    {
        $matcher = new Matcher();

        
        $request = new ConsumerRequest();
        $request
            ->setMethod('GET')
            ->setPath('/')
            ->addHeader('Accept', 'application/json');

        
        $response = new ProviderResponse();
        $response
            ->setStatus(200)
            ->addHeader('Content-Type', 'application/json')
            ->addHeader('Access-Control-Allow-Origin', '*')
            ->setBody([
                'message' => $matcher->term('world', '(world)')
            ]);

        $config  = new MockServerEnvConfig();
        $builder = new InteractionBuilder($config);
        $builder
            ->uponReceiving('A get request to /message')
            ->with($request)
            ->willRespondWith($response);

        $_SERVER['REQUEST_URI'] = '/message';
        require 'src/gateway.php';

        $builder->verify(); 

        $this->expectOutputRegex("/world/i");
    }
}
