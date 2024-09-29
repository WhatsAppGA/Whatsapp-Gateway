<div class="tab-pane fade " id="sendlist" role="tabpanel">
    <h3>Send List message API </h3>
    <p>Method : <code class="text-success">POST</code> | <code class="text-primary">GET</code></p>
    <p>Endpoint: <code>{{ env('APP_URL') }}/send-LIST</code></p>

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
                {{-- </tr>
           {
    "sender" : "6281284838163",
    "api_key" : "ndUJR38JkvyCfLZ",
    "number" : "082298859671",
    "name" : "pesan list",
    "footer" : "optional",
    "title" : "title list",
    "buttontext" : "ey",
    "message" : "Halo,ini pesan TEMPLATE button",
    "list" : ["sdf"]

} --}}
            <tr>
                <td>name</td>
                <td>string</td>
                <td>Yes</td>
                <td>name of your list</td>
            </tr>
            <tr>
                <td>footer</td>
                <td>string</td>
                <td>No</td>
                <td>footer of your message</td>
            </tr>
            <tr>
                <td>title</td>
                <td>string</td>
                <td>Yes</td>
                <td>title of your list</td>
            </tr>
            <tr>
                <td>buttontext</td>
                <td>string</td>
                <td>Yes</td>
                <td>text of your button list</td>
            </tr>
            <tr>
                <td>message</td>
                <td>string</td>
                <td>Yes</td>
                <td>Text of your message</td>
            </tr>
            <tr>
                <td>list</td>
                <td>array</td>
                <td>Yes</td>
                <td>list of your message min 1 max 10</td>
            </tr>
        </tbody>
    </table>
    <br>

    <p>Example JSON Request</p>
    <pre class="bg-dark text-white p-3">
                            <code>
{
    "sender" : "6282298859671",
    "api_key" : "ndUJR38JkvyCfLZ",
    "number" : "082298859671",
    "name" : "pesan list",
    "footer" : "optional",
    "title" : "title list",
    "buttontext" : "ey",
    "message" : "Halo,ini pesan list button",
    "list" : ["sdf","list2","list3"]

}

                            </code>
                        </pre>
    <br>
    <p>Example URL request</p>
    <pre class="bg-dark text-white p-3">
                            <code> 
{{ env('APP_URL') }}/send-list?sender=6282298859671&api_key=ndUJR38JkvyCfLZ&number=082298859671&name=pesan list&footer=optional&title=title list&buttontext=ey&message=Halo,ini pesan list&list=sdf,list2,list3
                            </code>
                        </pre>
    <br>
    
</div>
