<div class="tab-pane fade " id="sendtemplate" role="tabpanel">
    <h3>Send Template API</h3>
    <p>Method : <code class="text-success">POST</code> | <code class="text-primary">GET</code></p>
    <p>Endpoint: <code>{{ env('APP_URL') }}/send-template</code></p>

    <p>Request Body : (JSON If POST) </p>
    <table class="table">
        <thead>
            <tr>
                <th>Parameter</th>
                <th>Type</th>
                <th>Required</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>api_key</td>
                <td>string</td>
                <td>Yes</td>
                <td>API Key</td>
            </tr>
            <tr>
                <td>sender</td>
                <td>string</td>
                <td>Yes</td>
                <td>Number of your device</td>
            </tr>
            <tr>
                <td>number</td>
                <td>string</td>
                <td>Yes</td>
                <td>recipient number ex 72888xxxx|62888xxxx</td>
            </tr>
            <tr>
                <td>url</td>
                <td>string</td>
                <td>No</td>
                <td>url of your image</td>
            </tr>
            <tr>
                <td>footer</td>
                <td>string</td>
                <td>No</td>
                <td>footer of your message</td>
            </tr>
            <tr>
                <td>message</td>
                <td>string</td>
                <td>Yes</td>
                <td>Text of your message</td>
            </tr>
            <tr>
                <td>template</td>
                <td>array</td>
                <td>Yes</td>
                <td>array of button template Min 1 </td>
            </tr>
        </tbody>
    </table>
    <br>
    <p>Note : the template format must like : <span class="text-danger">call|Call me|6282298859671 or url|Visit
            Google|google.com</span>
        , the first is only call or url! </p>
    <p>Example JSON Request</p>
    <pre class="bg-dark text-white p-3">
                            <code>
 {
    "sender" : "6281284838163",
    "api_key" : "ndUJR38JkvyCfLZ",
    "number" : "082298859671",
    "url" : null,
    "footer" : "optional",
    "message" : "Halo,ini pesan TEMPLATE button",
    "template" : ["call|template 1|082298859671","url|tempate 2|google.com"]

}
                            </code>
                        </pre>
    <br>
    <p>Example URL request</p>
    <pre class="bg-dark text-white p-3">
                            <code>
{{ env('APP_URL') }}/send-template?sender=6282298859671&api_key=ndUJR38JkvyCfLZ&number=082298859671&url=&footer=optional&message=Halo,ini pesan TEMPLATE button&template=call|template 1|082298859671,url|tempate 2|google.com
                            </code>
                        </pre>
</div>
