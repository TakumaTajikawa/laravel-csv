<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ContactController extends Controller
{
    /**
     * 一覧ページの作成
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $contacts = Contact::select('contacts.*','c.name AS condition_name','d.name AS design_name')
            ->where('contacts.status', 1)
            ->leftJoin('conditions AS c', 'contacts.condition_id','=','c.id')
            ->leftJoin('designs AS d', 'contacts.design_id','=','d.id')
            ->orderBy('contacts.created_at', 'DESC')
            ->get();

        return view('index', compact('contacts'));
    }

    /**
     * CSVエクスポート
     * 
     * 今回はLaravelのStreamedResponseファザードを使って、ダウンロードダイアログを出す方法でCSVエクスポートを実装しています。 
     * CSVエクスポートには大きく分けて、以下2つの作業が必要になります。
     * CSVファイルに必要なデータを集め、整形する（SQL）リクエストを出したユーザーに
     * CSVファイルをダウンロードさせる2つの作業を全てコントローラーメソッドに書いてしまうと、
     * コントローラーメソッドが肥大化（ファットコントローラー）してしまいます。
     * そのため、データを集め整形する部分は getCsvData()として、モデルのメソッドに切り出して可読性を確保します。
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function csvExport(Request $request) {

        $post = $request->all();
        $response = new StreamedResponse(function () use ($request, $post) {
            
            // fopenはファイルまたはURLをブラウザで開くPHPの関数です。
            // fopenの引数に作成したCSVファイルを入れることでダウンロードを実現しています。
            $stream = fopen('php://output','w');

            $contact = new Contact();

            // 文字化け回避
            // stream_filter_prepend の引数に convert.iconv.utf-8/cp932//TRANSLIT を入れることで、
            // UTF-8へ文字列を変換してマルチバイト文字が入っていても文字化けがでないように対策しています。
            stream_filter_prepend($stream, 'convert.iconv.utf-8/cp932//TRANSLIT');

            // ヘッダー行を追加
            // fputcsvはその名の通り、データをCSVとして書き込む関数です。
            // データは配列にして渡す必要があるので、具体的なデータはモデルに任せてデータを書き込んでいます。
            fputcsv($stream, $contact->csvHeader());
            
            $results = $contact->getCsvData($post['start_date'], $post['end_date']);

            if (empty($results[0])) {
                    fputcsv($stream, [
                        'データが存在しませんでした。',
                    ]);
            } else {
                foreach ($results as $row) {
                    fputcsv($stream, $contact->csvRow($row));
                }
            }
            fclose($stream);
        });

        // レスポンスヘッダーはhttpリクエストに対する返答に付与する追加情報です。
        // レスポンスヘッダーにContent-Typeとファイル名を指定するとCSVファイルとしてダウンロードできます。
        // また、ユーザーが指定した期間をファイル名に変数で入力することによって、管理しやすくなるように工夫しています。
        $response->headers->set('Content-Type', 'application/octet-stream'); 
        $response->headers->set('content-disposition', 'attachment; filename='. $post['start_date'] . '〜' . $post['end_date'] . 'お問い合わせ一覧.csv');

        return $response;
    }
}
