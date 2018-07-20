<?php

namespace App\Http\Controllers;

use Berkayk\OneSignal\OneSignalClient;
use Berkayk\OneSignal\OneSignalFacade;
use Illuminate\Http\Request;
use Yajra\Pdo\Oci8;
use Yajra\Pdo\Oci8\Statement;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function login(Request $request)
    {
        $data = $request->all();


        $senha =  md5($data['password']);
        $id = $data['user'];

        // $xpto = \DB::select("select * from BRASMOB.BRMOB_USUARIOS where usu_in_codigo = (460) and senha = ('". $senha  ."')");

          $xpto = \DB::select("select * from BRASMOB.BRMOB_USUARIOS where usu_in_codigo = ('". $id ."') and senha = ('". $senha  ."')");

        return count($xpto);

    }

    public function index(Request $request)
    {
        $data = $request->all();
        $results = array();

        $conn = new Oci8(
            'oci:dbname=sarajevo.cocacola-bsb.com.br:1528/teste.*.db_recovery_;charset=UTF8',
            'BRASMOB',
            'BRASMOB'
        );

        $stmt = $conn->prepare("BEGIN BRMOB_ADM_ALCADA.GET_APROVACAOPEDIDO_(:cursor, " . $data['user_id'] . " , " . $data['filial'] . "); END;");

        $stmt->bindParam(':cursor', $cursor, \PDO::PARAM_STMT);

        $stmt->execute();

        $cursor_stmt = new Statement($cursor, $conn, [
            \PDO::ATTR_CASE => \PDO::CASE_LOWER
        ]);

        $cursor_stmt->execute();

        foreach ($cursor_stmt->fetchAll(\PDO::FETCH_ASSOC) as $item) {
            array_push($results, $item);
        }

        $conn->closeCursor($cursor);
        return response()->json(['data' => $results], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = [];
        $content = array(
            "en" => $data['message'] = 'Mensagem',

        );
        $heading = array(
            "en" => $data['heading'] = 'Titulo da mensagem'
        );

        $subtitle = array(
            "en" => $data['subtitle'] = 'Subtitulo da mensagem',
        );

        $url = array(
            "en" => "url"
        );
        $hashes_array = array();
        array_push($hashes_array, array(
            "id" => "like-button",
            "text" => "Like",
            "icon" => "http://i.imgur.com/N8SN8ZS.png",
            "url" => "https://brasal.com.br"
        ));
        array_push($hashes_array, array(
            "id" => "like-button-2",
            "text" => "Like2",
            "icon" => "http://i.imgur.com/N8SN8ZS.png",
            "url" => "https://yoursite.com"
        ));
        $fields = array(
            'app_id' => "2f1eab2a-f178-4d37-ab42-a3d89d97f560",
            'included_segments' => array(
                'All'
            ),
            'data' => array(
                "foo" => "bar",
                'subtitle' => 'Subtitle'
            ),
            'contents' => $content,
            'web_buttons' => $hashes_array,
            'headings' => $heading,
            'subtitle' => $subtitle,
            'url' => $url,
            'ios_badgeType' => 'Increase'
        );

        $fields = json_encode($fields);


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json; charset=utf-8',
            'Authorization: Basic ZGJkNDAwMjYtZjMyZi00YTJhLTgyNWQtNzc1OTk0NGRlYmU2'
        ));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        \Log::info('rdgfbuysdjknfgiodsfgjoijdfkm');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getDevices(){
        $app_id = "2f1eab2a-f178-4d37-ab42-a3d89d97f560";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/players?app_id=" . $app_id);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json',
            'Authorization: Basic ZGJkNDAwMjYtZjMyZi00YTJhLTgyNWQtNzc1OTk0NGRlYmU2'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }

    public function allusers()
    {
        return $this->getDevices();
    }

    function sendMessage(){
        $content = array(
            "en" => 'English Message'
        );

        $fields = array(
            'app_id' => "2f1eab2a-f178-4d37-ab42-a3d89d97f560",
            'include_player_ids' => array("38da8f63-4f22-49cf-b174-b6c9d70390a6"),
            'data' => array("foo" => "bar"),
            'contents' => $content,
            'ios_badgeType' => 'Increase',
            'ios_badgeCount' => 1
        );

        $fields = json_encode($fields);
        print("\nJSON sent:\n");
        print($fields);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8',
            'Authorization: Basic ZGJkNDAwMjYtZjMyZi00YTJhLTgyNWQtNzc1OTk0NGRlYmU2'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }


    public function send_to_rapha()
    {

        $response = $this->sendMessage();
        $return["allresponses"] = $response;
        $return = json_encode( $return);

        print("\n\nJSON received:\n");
        print($return);
        print("\n");

    }

    public function set_playerid(Request $request)
    {
        \Log::info($request);
        $data = $request->all();
        // $data['player_id'] = '38da8f63-4f22-49cf-b174-b6c9d70390a6';
        // $data['user_id'] = 5;
        \Log::info("************* \n Log da request ")
        \Log::info($data);
        \Log::info((" \n************");
        $save =  \DB::table("BRASMOB.BRMOB_USUARIOS")->where("USU_IN_CODIGO", '=', $data['user_id'])->update(['player_id' => $data['player_id']]);

        \DB::commit();

        return $save;
    }

    public function todos_usuarios()
    {
        return \DB::select("select * from BRASMOB.BRMOB_USUARIOS");
    }


}
