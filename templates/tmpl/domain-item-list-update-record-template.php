<script type="text/html" id="tmpl-domain-item-list-update-record-template">
    <tr data-id="{{data.id}}">
        <td style="width: 50px;">
            <label class="fancy-checkbox">
                <input class="checkbox-tick" type="checkbox" name="checkbox">
                <span></span>
            </label>
        </td>
        <td>
            <div class="form-group">
                <input type="text" class="form-control  name" name="name" id="name" value="{{data.name}}">
            </div>
        </td>
        <td>
            <div class="form-group">
                <select id="type" name="type" class="form-control type">
                    <option value="A" <# if ( data.type=="A" ) { #>selected<# } #>>A (IP Address)</option>
                    <option value="CNAME" <# if ( data.type=="CNAME" ) { #>selected<# } #>>CNAME (Alias)</option>
                    <option value="MX" <# if ( data.type=="MX" ) { #>selected<# } #>>MX (Mail Exchange)</option>
                    <option value="REDIRECT" <# if ( data.type=="REDIRECT" ) { #>selected<# } #>>URL Redirect</option>
                    <option value="DOMAIN_REDIRECT" <# if ( data.type=="DOMAIN_REDIRECT" ) { #>selected<# } #>>Domain Redirect</option>
                    <option value="FRAME" <# if ( data.type=="FRAME" ) { #>selected<# } #>>URL Frame</option>
                    <option value="TXT" <# if ( data.type=="TXT" ) { #>selected<# } #>>TXT(Text)</option>
                    <option value="AAAA" <# if ( data.type=="AAAA" ) { #>selected<# } #>>AAAA (IPV6 Host)</option>
                    <option value="SRV" <# if ( data.type=="SRV" ) { #>selected<# } #>>SRV Record</option>
                </select>
            </div>
        </td>
        <td>
            <div class="form-group">
                <input type="text" class="form-control  value" name="value" id="value" value="{{data.value}}">
            </div>
        </td>
        <td>
            <div class="form-group">
                <select id="ttl" name="ttl" class="form-control ttl">
                    <option value="300" <# if ( data.ttl=="300" ) { #>selected<# } #>>5 phút</option>
                    <option value="600" <# if ( data.ttl=="600" ) { #>selected<# } #>>10 phút</option>
                    <option value="900" <# if ( data.ttl=="900" ) { #>selected<# } #>>15 phút</option>
                    <option value="1800" <# if ( data.ttl=="1800" ) { #>selected<# } #>>30 phút</option>
                    <option value="3600" <# if ( data.ttl=="3600" ) { #>selected<# } #>>1 tiếng</option>
                    <option value="7200" <# if ( data.ttl=="7200" ) { #>selected<# } #>>2 tiếng</option>
                    <option value="18000" <# if ( data.ttl=="18000" ) { #>selected<# } #>>5 tiếng</option>
                    <option value="43200" <# if ( data.ttl=="43200" ) { #>selected<# } #>>12 tiếng</option>
                    <option value="86400" <# if ( data.ttl=="86400" ) { #>selected<# } #>>1 ngày</option>
                </select>
            </div>
        </td>
        <td>
            <div class="form-group">
                <button type="button" class="btn btn-small btn-danger btn-delete" title="Xóa"><i class="fa fa-trash-o"></i></button>
                <button type="button" class="btn btn-small btn-info  btn-save" title="Lưu"><i class="fa fa-cloud"></i></button>
            </div>
        </td>
    </tr>
</script>