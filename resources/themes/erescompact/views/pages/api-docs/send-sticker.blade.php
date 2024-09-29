  <div class="tab-pane fade  " id="sendsticker" role="tabpanel">
      <h3>Send Sticker API</h3>
      <p>Method : <code class="text-success">POST</code> | <code class="text-primary">GET</code></p>
      <p>Endpoint: <code>{{ env('APP_URL') }}/send-sticker</code></p>

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
                  <td>url</td>
                  <td>string</td>
                  <td>Yes</td>
                  <td>URL of media, must direct link</td>
              </tr>

          </tbody>
      </table>

      <p>Note: Make sure the url is direct link, not a link from google drive or other cloud storage</p>
      <br>
      <p>Example JSON Request </p>
      <pre class="bg-dark text-white">
        <code>
          {
            "api_key": "1234567890",
            "sender": "62888xxxx",
            "number": "62888xxxx",
            "url": "https://example.com/image.jpg"
          }
        </code>

        
      </pre>
      <p>Example URL Request</p>
      <pre class="bg-dark text-white">
          <code class="json">
  {{ env('APP_URL') }}/send-sticker?api_key=1234567890&sender=62888xxxx&number=62888xxxx&url=https://example.com/image.jpg
          </code>
        </pre>
  </div>
