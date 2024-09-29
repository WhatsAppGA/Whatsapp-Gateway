<div class="tab-pane fade " id="sendlocation" role="tabpanel">
    <h3>Send Location API</h3>
    <p>Method : <code class="text-success">POST</code> | <code class="text-primary">GET</code></p>
    <p>Endpoint: <code>{{ env('APP_URL') }}/send-location</code></p>

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
                <td>latitude</td>
                <td>string</td>
                <td>Yes</td>
                <td>latitude number ex 24.121231</td>
            </tr>
			<tr>
                <td>Longitude</td>
                <td>string</td>
                <td>Yes</td>
                <td>longitude number ex 55.1121221</td>
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
          "latitude": "24.121231",
          "longitude": "55.1121221",
        }
      </code>
      </pre>
    <p>Example URL Request</p>
    <pre class="bg-dark text-white">
        <code class="json">
{{ env('APP_URL') }}/send-location?api_key=1234567890&sender=62888xxxx&number=62888xxxx&latitude=24.121231&longitude=55.1121221
        </code>
      </pre>


</div>
