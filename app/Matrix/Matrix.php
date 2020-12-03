<?php 
namespace App\Matrix;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\ClientException;
use App\Matrix\UserSession;
use App\Matrix\Room;
use Illuminate\Support\Facades\Log;

class Matrix{

    public $session;
    public $room;
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
            throw new \Exception("Domain is empty.");
        }
    }

    public function setupResources(){
        Log::info("Setup matrix resources");
        $this->baseUrl = $this->domain.'_matrix/client/r0';
        $this->client = new Client(['verify' => false]);
        $this->session = new UserSession($this);
        $this->room = new Room($this);
    }

    public function showDebugInfo(){
        echo("MatrixServiceProviderDebugInfo<br>");
        echo("BaseUrl:$this->baseUrl");
    }
    public function session(){
      return $this->session;
    }

    public function updateSession(){
        $this->session->getDataFromSession();
        $this->room->getDataFromSession();
    }
    /**
     * Internal method for handling requests
     *
     * @internal
     * @param $method
     * @param $endpoint
     * @param array|null $data
     * @param array|null $query
     * @return mixed|null
     * @throws ApiException
     * @throws ConflictingStateException
     * @throws RateLimitExceededException
     * @throws UnsupportedContentTypeException
     */
    public function request($method, $endpoint, array $data = null, array $query = null, $rawData = false)
    {
        $options = ['json' => $data];

        if ($rawData) {
            $options = $data;
        }

        if (isset($query)) {
            $options['query'] = $query;
        }

        $url = $this->baseUrl . $endpoint;

        return $this->performRequest($method, $url, $options);
    }

    /**
     * Performs the request
     *
     * @internal
     *
     * @param $method
     * @param $url
     * @param $options
     * @return mixed|null
     */
    private function performRequest($method, $url, $options)
    {
        try {
            switch ($method) {
                case 'GET':
                    return json_decode($this->client->get($url, $options)->getBody(), true);
                case 'POST':
                    return json_decode($this->client->post($url, $options)->getBody(), true);
                case 'PUT':
                    return json_decode($this->client->put($url, $options)->getBody(), true);
                case 'DELETE':
                    return json_decode($this->client->delete($url, $options)->getBody(), true);
                default:
                    return null;
            }
        } catch (RequestException $e) {
            Log::error('Request Exception in Matrix Library.');
            $response = $e->getResponse()->getBody()->getContents();
            Log::info(gettype($response));
            // make sure the response is an array too
            return json_decode($response, true);
        } catch (ConnectException $e) {
            Log::error('Connect Exception in Matrix Library.');
            return null;
        } catch (ClientException $e) {
            Log::error('Client Exception in Matrix Library.');
            return null;
        }
    }
}
