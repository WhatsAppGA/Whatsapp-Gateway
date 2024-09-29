 <div class="tab-pane fade  " id="sendbutton" role="tabpanel">
     <h3>Send Button API</h3>
     <p>Method : <code class="text-success">POST</code> | <code class="text-primary">GET</code></p>
     <p>Endpoint: <code>{{ env('APP_URL') }}/send-button</code></p>

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
                 <td>message</td>
                 <td>string</td>
                 <td>Yes</td>
                 <td>Text of message</td>
             </tr>
             <tr>
                 <td>button</td>
                 <td>array</td>
                 <td>Yes</td>
                 <td>Button array 5</td>
             </tr>
             <tr>
                 <td>footer</td>
                 <td>string</td>
                 <td>No</td>
                 <td>The footer text of message</td>
             </tr>
             <tr>
                 <td>url</td>
                 <td>string</td>
                 <td>No</td>
                 <td>Image or video url</td>
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
     "url" : null,
     "footer" : "optional",
     "message" : "Halo,ini pesan button",
     "button" : ["button 1","button 2","button 3"]

 }
                            </code>
                        </pre>
     <p> Example URL</p>
     <pre class="bg-dark text-white">
                            <code class="json">
    {{ env('APP_URL') }}/send-button?sender=6281284838163&api_key=yourapikey&number=082298859671&url=&footer=optional&message=Halo,ini pesan button&button=button 1,button 2,button 3 
    </code>
                        </pre>


 </div>
