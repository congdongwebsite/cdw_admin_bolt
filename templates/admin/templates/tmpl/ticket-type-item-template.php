<script type="text/html" id="tmpl-ticket-type-item-template">
    <li data-type="{{data.id}}" class="{{data.id}}-ticket 
    <# if ( data.active ) { #>
        active
        <# } #>
    ">
        <a href="javascript:void(0);"><i class="{{data.icon}}"></i>{{data.text}}
            <span class="badge badge-{{data.color}} float-right number">{{data.count}}</span>
        </a>
    </li>
</script>