<?php
/**
 * @author  mfris
 */
namespace AppBundle\Service;

use AppBundle\Service\Download\Downloader;
use AppBundle\Service\Import\Importer;
use AppBundle\Service\Import\Result;
use SplFileObject;

/**
 * Class DownloadingImporer
 * @author mfris
 * @package AppBundle\Service
 */
final class DownloadingImporter
{

    /**
     * @var Downloader
     */
    private $downloader;

    /**
     * @var Importer
     */
    private $importer;

    /**
     * @var string
     */
    private $defaultDownloadUrl;

    /**
     * DownloadingImporter constructor.
     *
     * @param string $defaultDownloadUrl,
     * @param Downloader $downloader
     * @param Importer $importer
     */
    public function __construct(string $defaultDownloadUrl, Downloader $downloader, Importer $importer)
    {
        $this->defaultDownloadUrl = $defaultDownloadUrl;
        $this->downloader = $downloader;
        $this->importer = $importer;
    }

    /**
     * @param string $graphFileUrl
     * @return Result
     */
    public function downloadAndImport(string $graphFileUrl = '') : Result
    {
        $file = $this->downloadGraphFile($graphFileUrl);

        return $this->importer->import($file);
    }

    /**
     * @param string $graphFileUrl
     * @return SplFileObject
     */
    private function downloadGraphFile(string $graphFileUrl) : SplFileObject
    {
        if ($graphFileUrl === '') {
            $graphFileUrl = $this->defaultDownloadUrl;
        }

        return $this->downloader->download($graphFileUrl);
    }
}
