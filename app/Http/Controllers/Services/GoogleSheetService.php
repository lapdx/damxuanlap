<?php

namespace App\Http\Controllers\Services;

use Exception;
use Google_Client;
use Google_Service_Sheets;
use Google_Service_Sheets_ValueRange;
use Illuminate\Http\Request;
use function base_path;
use function GuzzleHttp\json_decode;
use function GuzzleHttp\json_encode;
use function response;

/**
 * Description of GoogleSheetService
 *
 * @author lapdx
 */
const SCOPES = Google_Service_Sheets::SPREADSHEETS;

class GoogleSheetService extends BaseService {

    public function pushToSheet(Request $request) {
        try {
            $data = $request->input("data");
            $token = $request->input("token");
            if (empty($token) || $token != "damxuanlap1w6l") {
                throw new Exception("token not valid!");
            }
            if (!is_array($data) || empty($data)) {
                throw new Exception("Dữ liệu đầu vào không hợp lệ!");
            }
            $client = $this->getClient();
            $service = new Google_Service_Sheets($client);
//        https://docs.google.com/spreadsheets/d/1w6l-p2W5hEOb6v_94z456gJPZGfLIHIouZanzMDligk/edit#gid=0
            $spreadsheetId = "1w6l-p2W5hEOb6v_94z456gJPZGfLIHIouZanzMDligk";
//        $ranges = array(
//            "Sheet2!A1:D5"
//        );
            $range = "Sheet1";
            $values = array(
                array(
                    "Wheel", "$20.50", "4", "3/1/2016",
                ),
                array(
                    "Wheel2", "$20.50", "4", "3/1/2016",
                ),
                    // Additional rows ...
            );
            $values = $data;
            $body = new Google_Service_Sheets_ValueRange(array(
                'values' => $values
            ));

            $params = array(
                'valueInputOption' => "USER_ENTERED",
                'insertDataOption' => "INSERT_ROWS"
            );
            $response = ["status" => "successful"];
            $result = $service->spreadsheets_values->append($spreadsheetId, $range, $body, $params);
        } catch (Exception $exc) {
            $response = ["status" => "fail", "message" => $exc->getMessage()];
        }

        return response()->json($response);
    }

    private function getClient() {
        $client = new Google_Client();
        $client->setApplicationName("Sheet API");
        $client->setScopes(SCOPES);

        $client->setAuthConfig(base_path() . "/public" . "/damxuanlap.json");
        $client->setAccessType('offline');

        // Load previously authorized credentials from a file.
        $credentialsPath = base_path() . "/public" . "/sheet.json";
        if (file_exists($credentialsPath)) {
            $accessToken = json_decode(file_get_contents($credentialsPath), true);
        } else {
            // Request authorization from the user.
            $authUrl = $client->createAuthUrl();
            printf("Open the following link in your browser:\n%s\n", $authUrl);
            print 'Enter verification code: ';
            $authCode = trim("4/wb_2jjLju1QSiSM3qzP1hajDq8TOGF9jBa-5EyGtvmY");

            // Exchange authorization code for an access token.
            $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);

            // Store the credentials to disk.
            if (!file_exists(dirname($credentialsPath))) {
                mkdir(dirname($credentialsPath), 0700, true);
            }
            file_put_contents($credentialsPath, json_encode($accessToken));
            printf("Credentials saved to %s\n", $credentialsPath);
        }
        $client->setAccessToken($accessToken);

        // Refresh the token if it's expired.
        if ($client->isAccessTokenExpired()) {
            $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
            file_put_contents($credentialsPath, json_encode($client->getAccessToken()));
        }
        return $client;
    }

}
