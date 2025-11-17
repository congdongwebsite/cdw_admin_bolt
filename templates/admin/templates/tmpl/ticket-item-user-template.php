<script type="text/html" id="tmpl-ticket-item-user-template">
    <li class="clearfix 
    <# if ( data.isRead ) { #>
        unread
                    <# } #>
     ticket-item" data-id-ticket="{{data.id}}">
        <div class="ticket-detail-left">
            <label class="fancy-checkbox">
                <input type="checkbox" name="tick" class="checkbox-tick">
                <span></span>
            </label>

            <a href="javascript:void(0);" class="ticket-important ticket-star  
    <# if ( data.isImportant ) { #>
        active
                    <# } #>
                    ">
                <i class=" fa fa-star"></i></a>

        </div>
        <div class="ticket-detail-right ticket-detail-right d-flex flex-row flex-wrap justify-content-between">
            <div>
                <h6 class="sub"><a href="{{data.link}}" target="_blank" class="ticket-detail-expand"><strong>{{data.title}}</strong></a></h6>
                <p class="dep" style="white-space: pre-wrap;">{{data.content}}</p>
                <div class="mt-3 types">

                </div>
            </div>
            <span class="time">

                <# if ( data.hasAttach ) { #>
                    <i class="fa fa-paperclip mr-2"></i>
                    <# } #>
                        {{data.date}}
            </span>
        </div>
    </li>
</script>