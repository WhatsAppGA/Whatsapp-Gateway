<div class="tab-pane fade e" id="disconnectdevice" role="tabpanel">
    <h3>Disconnect device</h3>
    <p>Method : <code class="text-success">POST</code>
    <p>Endpoint: <code>{{ env('APP_URL') }}/logout-device</code></p>

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
            {{-- {
            "device" : "6282298859671",
            "api_key" : "ndUJR38JkvyCfLZ"
  } --}}
            <tr>
                <td>sender</td>
                <td>string</td>
                <td>Yes</td>
                <td>Device you want log out</td>

            </tr>
            <tr>
                <td>api_key</td>
                <td>string</td>
                <td>Yes</td>
                <td>API Key</td>
            </tr>

        </tbody>
    </table>
    <br>
    <p>Normal Response</p>
    <pre class="bg-dark text-white">
      <code>
{
    "status": true,
    "message": "device disconnected "
}


      </code>
      </pre>



</div>
