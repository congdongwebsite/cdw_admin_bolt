<script type="text/html" id="tmpl-ticket-item-template">
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
                    "> <i class=" fa fa-star"></i></a>

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
        <div class="hover-action">
            <# if ( data.isArchive ) { #>
                <a class="btn btn-warning mr-2 ticket-archive" href="javascript:void(0);" title="Bỏ lưu trữ"><i class="icon-envelope"></i></a>
                <# } #>

                    <# if ( data.isTrash ) { #>
                        <button type="button" data-type="confirm" class="btn btn-danger ticket-trash" title="Hoàn lại tiếp nhận"><i class="icon-action-undo"></i></button>
                        <# } #>

                            <# if ( !data.isTrash && !data.isArchive ) { #>
                                <a class="btn btn-warning mr-2 ticket-archive" href="javascript:void(0);" title="Lưu trữ"><i class=" fa fa-archive"></i></a>
                                <button type="button" data-type="confirm" class="btn btn-danger ticket-trash" title="Xóa"><i class="fa fa-trash-o"></i></button>
                                <# } #>

        </div>
    </li>
</script>