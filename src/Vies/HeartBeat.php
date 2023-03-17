<?php

declare(strict_types=1);

namespace Webgriffe\SyliusItalianInvoiceableOrderPlugin\Vies;

use DragonBe\Vies\HeartBeat as BaseHeartBeat;
use DragonBe\Vies\Vies;
use InvalidArgumentException;
use RuntimeException;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class HeartBeat extends BaseHeartBeat
{
    private const DEFAULT_TIMEOUT = 10;

    public function isAlive(): bool
    {
        if (false === self::$testingEnabled) {
            return $this->reachOut();
        }

        return self::$testingServiceIsUp;
    }

    /**
     * A private routine to send a request over a socket to
     * test if the remote service is responding with a status
     * code of 200 OK. Now supports also proxy connections.
     */
    private function reachOut(): bool
    {
        try {
            $data = $this->getSecuredResponse();
        } catch (RuntimeException $runtimeException) {
            return false;
        }

        return
            (0 === strcmp('HTTP/1.1 200 OK', (string) $data[0])) ||
            (0 === strcmp('HTTP/1.1 307 Temporary Redirect', (string) $data[0]))
        ;
    }

    /**
     * This method will make a simple request inside a stream
     * resource to retrieve its contents. Useful inside secured
     * streams.
     *
     * @param resource|mixed $handle
     */
    private function readContents($handle): array
    {
        if (!is_resource($handle)) {
            throw new InvalidArgumentException('Expecting a resource to be provided');
        }
        $response = '';
        $uri = sprintf('%s://%s/taxation_customs/vies', Vies::VIES_PROTO, $this->host);
        $stream = [
            'GET ' . $uri . ' HTTP/1.0',
            'Host: ' . $this->host,
            'Connection: close',
        ];
        fwrite($handle, implode("\r\n", $stream) . "\r\n\r\n");
        while (!feof($handle)) {
            $response .= fgets($handle, 1024);
        }
        fclose($handle);
        $response = str_replace("\r\n", \PHP_EOL, $response);

        return explode(\PHP_EOL, $response);
    }

    /**
     * Will make a secured request over SSL/TLS where this
     * method will first create a secured stream before
     * making the request.
     *
     * @throws RuntimeException
     *
     * @see https://bytephunk.wordpress.com/2017/11/27/ssl-tls-stream-sockets-in-php-7/
     */
    private function getSecuredResponse(): array
    {
        $streamOptions = [
            'ssl' => [
                'verify_peer' => true,
                'verify_peer_name' => true,
                'allow_self_signed' => false,
            ],
        ];
        $streamContext = stream_context_create($streamOptions);
        $socketAddress = sprintf(
            'tls://%s:%d',
            $this->host,
            $this->port,
        );
        $error = null;
        $errno = null;
        $stream = stream_socket_client(
            $socketAddress,
            $errno,
            $error,
            self::DEFAULT_TIMEOUT,
            \STREAM_CLIENT_CONNECT,
            $streamContext,
        );

        if ($stream === false) {
            throw new RuntimeException('Can not create socket stream: ' . $error);
        }

        return $this->readContents($stream);
    }
}
