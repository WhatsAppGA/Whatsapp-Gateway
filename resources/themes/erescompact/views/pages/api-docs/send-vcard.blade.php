<div class="tab-pane fade " id="sendvcard" role="tabpanel">
    <h3>Send VCard API</h3>
    <p>Method : <code class="text-success">POST</code> | <code class="text-primary">GET</code></p>
    <p>Endpoint: <code>{{ env('APP_URL') }}/send-vcard</code></p>

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
                <td>name</td>
                <td>string</td>
                <td>Yes</td>
                <td>Name ex magd almuntaser</td>
            </tr>
			<tr>
                <td>phone</td>
                <td>string</td>
                <td>Yes</td>
                <td>Phone Number ex 72888xxxx|62888xxxx</td>
            </tr>
        </tbody>
    </table>
    <br>
    <p>Examplo JSON Request</p>
    <pre class="bg-dark text-white">
      <code>
        {
          "api_key": "1234567890",
          "sender": "62888xxxx",
          "number": "62888xxxx",
          "name": "magd",
          "phone": "62822xxxx",
        }
      </code>
      </pre>
    <p>Example URL Request</p>
    <pre class="bg-dark text-white">
        <code class="json">
{{ env('APP_URL') }}/send-vcard?api_key=1234567890&sender=62888xxxx&number=62888xxxx&name=magd&phone=62822xxxx
        </code>
      </pre>


</div>
