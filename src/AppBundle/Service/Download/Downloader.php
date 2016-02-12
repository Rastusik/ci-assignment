<?php
/**
 * @author  mfris
 */
namespace AppBundle\Service\Download;

use GuzzleHttp\ClientInterface;
use SplFileObject;
use RuntimeException;

/**
 * Class Downloader
 * @author mfris
 * @package AppBundle\Service\Download
 */
final class Downloader
{

    /**
     * @var ClientInterface
     */
    private $httpClient;

    /**
     * @var string
     */
    private $downloadFolder;

    /**
     * Downloader constructor.
     * @param string $downloadFolder
     * @param ClientInterface $httpClient
     */
    public function __construct(string $downloadFolder, ClientInterface $httpClient)
    {
        $this->setDownloadFolder($downloadFolder);
        $this->httpClient = $httpClient;
    }

    /**
     * @param string $downloadFolder
     * @return Downloader
     * @throws RuntimeException
     */
    public function setDownloadFolder(string $downloadFolder) : Downloader
    {
        if (!file_exists($downloadFolder)) {
            throw new RuntimeException("Folder doesn't exist: {$downloadFolder}");
        }

        if (!is_dir($downloadFolder)) {
            throw new RuntimeException("Not a directory: {$downloadFolder}");
        }

        if (!is_writable($downloadFolder)) {
            throw new RuntimeException("Not writable: {$downloadFolder}");
        }

        $this->downloadFolder = $downloadFolder;

        return $this;
    }

    /**
     * @param string $url
     * @return SplFileObject
     */
    public function download(string $url) : SplFileObject
    {
        $fileName = $this->generateTempFileName();
        $response = $this->httpClient->request('GET', $url, ['sink' => $fileName]);

        if ($response->getStatusCode() !== 200) {
            throw new RuntimeException("Error downloading file.");
        }

        return new SplFileObject($fileName);
    }

    /**
     * @return string
     */
    private function generateTempFileName() : string
    {
        return tempnam($this->downloadFolder, "graph_");
    }
}
