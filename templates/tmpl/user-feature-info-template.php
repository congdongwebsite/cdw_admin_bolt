<script type="text/html" id="tmpl-user-feature-info-template">
    <# if ( data.info1.found ) { #>
        <li class="{{data.info1.class}}" title="{{data.info1.title}}">
            <small>{{data.info1.text}}</small>
            <h6></i><span class="value">{{data.info1.value}}</span></h6>
        </li>
        <# } #>
            <# if ( data.info2.found ) { #>
                <li class="{{data.info2.class}}" title="{{data.info2.title}}">
                    <small>{{data.info2.text}}</small>
                    <h6></i><span class="value">{{data.info2.value}}</span></h6>
                </li>
                <# } #>
                    <# if ( data.info3.found ) { #>
                        <li class="{{data.info3.class}}" title="{{data.info3.title}}">
                            <small>{{data.info3.text}}</small>
                            <h6></i><span class="value">{{data.info3.value}}</span></h6>
                        </li>
                        <# } #>
</script>