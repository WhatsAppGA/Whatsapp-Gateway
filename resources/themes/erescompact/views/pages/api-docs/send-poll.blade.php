<div class="tab-pane fade  " id="sendpoll" role="tabpanel">
    <h3>Send Poll  API</h3>
    <p>Method : <code class="text-success">POST</code> | <code class="text-primary">GET</code></p>
    <p>Endpoint: <code>{{ env('APP_URL') }}/send-poll</code></p>

    <p>Request Body : (JSON If POST)
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
                <td>name</td>
                <td>string</td>
                <td>Yes</td>
                <td>name or question polling</td>
            </tr>
            <tr>
                <td>option</td>
                <td>array</td>
                <td>Yes</td>
                <td>values of poll message</td>
            </tr>
            <tr>
                <td>countable</td>
                <td>string 1 or 0</td>
                <td>Yes</td>
                <td>is polling only one number / poll or allow multiple</td>
            </tr>
           
            

        </tbody>
    </table>
    <p>Example json</p>
    <pre class="bg-dark text-white">
                           <code class="json">
{
    "sender" : "6281284838163",
    "api_key" : "yourapikey",
    "number" : "082298859671",
    "countable" : "1",
    "name" : "what color do you like?",
    "option" : ["red","blue","yellow"]

}
                           </code>
                       </pre>
    <p> Example URL</p>
    <pre class="bg-dark text-white">
                           <code class="json">
   {{ env('APP_URL') }}/send-button?sender=6281284838163&api_key=yourapikey&number=082298859671&name=what color do you like&button=red ,blue,yellow
   </code>
                       </pre>


</div>
