<?php

namespace App\Tests\Lib;

use App\Entity\Token;
use App\Entity\User;
use App\Repository\TokenRepository;
use PHPUnit\Framework\Assert;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MyWebTestCase extends WebTestCase
{
    /**
     * @var \Symfony\Bundle\FrameworkBundle\KernelBrowser|null
     */
    protected static $client = null;

    /**
     * @var array
     */
    protected array $requestHeaders = [];

    /**
     * @var int
     */
    protected int $expectedStatus = Response::HTTP_OK;

    /**
     * @var Response
     */
    protected Response $response;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        if (!self::$client) {
            self::$client = static::createClient();
        }

        $this->setHeader('CONTENT_TYPE', 'application/json');
        $this->setHeader('HTTP_Authorization', 'some-token');
    }

    /**
     * @param int $expectedStatus
     * @return $this
     */
    public function setExpectedStatusCode(int $expectedStatus): self
    {
        $this->expectedStatus = $expectedStatus;

        return $this;
    }

    /**
     * @return $this
     */
    protected function assertResponse(): self
    {
        Assert::assertEquals(
            $this->expectedStatus,
            $this->response->getStatusCode(),
            $this->response->getContent()
        );

        return $this;
    }

    /**
     * @param string $name
     * @param string $value
     * @return $this
     */
    public function setHeader(string $name, string $value): self
    {
        $this->requestHeaders[$name] = $value;

        return $this;
    }

    /**
     * @param string $method
     * @param string $path
     * @param array $data
     * @return $this
     */
    public function sendRequest(string $method, string $path, array $data = []): self
    {
        $requestParameters = Request::METHOD_GET === $method ? $data : [];
        $data = Request::METHOD_GET === $method ? null : json_encode($data, JSON_PRESERVE_ZERO_FRACTION);

        self::$client->request(
            $method,
            $path,
            $requestParameters,
            [], // files
            $this->requestHeaders,
            $data
        );

        $this->response = self::$client->getResponse();

        $this->assertResponse();

        return $this;
    }

    /**
     * @return array|string|null
     */
    protected function getContent(): array|string|null
    {
        return json_decode($this->response->getContent(), true);
    }

    /**
     * @return $this
     */
    protected function authorizeDefaultUser()
    {
        $tokenRepositoryMock = $this->createMock(TokenRepository::class);
        $tokenRepositoryMock->expects($this->any())
            ->method('find')
            ->willReturn(
                (new Token())
                    ->setToken('some-mega-token')
                    ->setExpirationDate(new \DateTime('+1 year'))
                    ->setUser((new User()))
            );
        $tokenRepositoryMock->expects($this->any())
            ->method('isTokenValid')
            ->willReturn(true);

        self::getContainer()->set(TokenRepository::class, $tokenRepositoryMock);

        return $this;
    }
}