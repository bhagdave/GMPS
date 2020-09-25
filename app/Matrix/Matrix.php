<?php 
namespace App\Matrix;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Exception;
use App\Matrix\MatrixSession;

class Matrix{

    public $session;
    protected $client;
    private $baseUrl;
    private $domain;

    /**
     * Constructs a new matrix api instance
     *
     * @matrix
     * @param string $domain
     * @throws Exceptions\InvalidConfigurationException
     */
    public function __construct($domain)
    {
        $this->validateConstructorArgs($domain);
        $this->domain = $domain;
        $this->setupResources();
    }

    private function validateConstructorArgs($domain)
    {
        if (!isset($domain)) {
            throw new Exception("Domain is empty.");
        }
    }

    private function setupResources(){
        $this->baseUrl = $this->domain.'_matrix/client/r0';
        $this->client = new Client(['verify' => false]);
        $this->session = new MatrixSession($this);
    }

    public function showDebugInfo(){
        echo("MatrixServiceProviderDebugInfo<br>");
        echo("BaseUrl:$this->baseUrl");
    }
}
