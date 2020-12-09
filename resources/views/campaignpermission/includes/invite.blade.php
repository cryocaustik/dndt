<div class="modal" tabindex="-1" role="dialog" id="invite-modal">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Invite to SOMECAMPAIGN</h5>
            </div>
            <div class="modal-body">
                <input type="hidden" name="id" id="campaign_id">
                <div class="form-group">
                    <label for="expires">Expires in</label>
                    <select name="expires" id="expires" class="form-control">
                        <option value="5m">5 minutes</option>
                        <option value="30m" selected>30 minutes</option>
                        <option value="1h">1 hour</option>
                        <option value="1d">1 day</option>
                        <option value="7d">7 days</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="permission">Permission</label>
                    <select name="permission" id="permission" class="form-control">
                        <option value="reader">Reader</option>
                        <option value="contributor" selected>Contributor</option>
                        <option value="administrator">Administrator</option>
                    </select>
                </div>
                <div class="form-group" id="invite-link-group" style="display: none;">
                    <label for="invite-link">Invite Link</label>
                    <input type="text" class="form-control" name="invite-link" id="invite-link">
                </div>
            </div>
            <div class="modal-footer">
                <div class="btn btn-primary mr-auto" onclick="createInvite()">
                    Create invite
                </div>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>
<script>
    function inviteModal(id){
        $('#campaign_id').val(id);
        $('#invite-modal').modal();
    }

    function createInvite(){
        let campaign_id = $('#campaign_id').val();
        let expires = $('#expires').val();
        let permission = $('#permission').val();
        $.ajax({
            url: "{{ route('campaign.permission.invite') }}",
            type: 'POST',
            headers: {
                'X-CSRF-Token': "{{ csrf_token() }}"
            },
            data: {
                campaign_id,
                expires,
                permission
            },
            success(data){
                console.log('success')
                console.log(data)
                $('#invite-link-group').show();
                $('#invite-link').val(data.invite);
            },
            error(err){
                console.log('failed')
                console.log(err)
            }
        })
    }

</script>
