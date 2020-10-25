<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

use PhpPact\Consumer\InteractionBuilder;
use PhpPact\Consumer\Matcher\Matcher;
use PhpPact\Consumer\Model\ConsumerRequest;
use PhpPact\Consumer\Model\ProviderResponse;
use PhpPact\Standalone\MockService\MockServerEnvConfig;


final class ConsumerHelloServiceTest extends TestCase
{
    
    public function setUp(): void {
        parent::setUp();
        putenv('HELLO_SERVICE_URL=localhost:7200');
        putenv('WORLD_SERVICE_URL=localhost:7200');
    }
    
    public function testGetHelloString(): void
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
            ->setBody([
                'message' => $matcher->term('Hello', '(Hello)')
            ]);

        $config  = new MockServerEnvConfig();
        $builder = new InteractionBuilder($config);
        $builder
            ->uponReceiving('A get request to /')
            ->with($request)
            ->willRespondWith($response);

        require 'src/gateway.php';

        $builder->verify(); 

        $this->expectOutputRegex("/hello/i");
    }
}
