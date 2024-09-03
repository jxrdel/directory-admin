<!-- Modal -->
<div wire:ignore.self class="modal fade" id="editRecordModal" tabindex="-1" aria-labelledby="editRecordModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="editRecordModalLabel" style="color: black; text-align:center">Edit Record</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form wire:submit.prevent="editRecord" action="">
            <div class="modal-body" style="color: black">
                
                <div class="row" style="margin-top:10px">

                    <div class="col" style="display: flex;margin-left: 80px">
                        <div class="col-3">
                            <label style="margin-top:5px;" for="title">Employee Name: &nbsp;</label>
                        </div>
                        <div class="col">
                            <input class="form-control" wire:model="employeename" type="text" style="width: 70%;color:black;margin-left:50px">
                        </div>
                    </div>

                </div>
                
                <div class="row" style="margin-top:10px">

                    <div class="col" style="display: flex;margin-left: 80px">
                        <div class="col-3">
                            <label style="margin-top:5px;" for="title">Department: &nbsp;</label>
                        </div>
                        <div class="col">
                            <input class="form-control" wire:model="department" type="text" style="width: 70%;color:black;margin-left:50px">
                        </div>
                    </div>

                </div>
                
                <div class="row" style="margin-top:10px">

                    <div class="col" style="display: flex;margin-left: 80px">
                        <div class="col-3">
                            <label style="margin-top:5px;" for="title">Unit: &nbsp;</label>
                        </div>
                        <div class="col">
                            <input class="form-control" wire:model="unit" type="text" style="width: 70%;color:black;margin-left:50px">
                        </div>
                    </div>

                </div>
                
                <div class="row" style="margin-top:10px">

                    <div class="col" style="display: flex;margin-left: 80px">
                        <div class="col-3">
                            <label style="margin-top:5px;" for="title">Position: &nbsp;</label>
                        </div>
                        <div class="col">
                            <input class="form-control" wire:model="position" type="text" style="width: 70%;color:black;margin-left:50px">
                        </div>
                    </div>

                </div>
                
                <div class="row" style="margin-top:10px">

                    <div class="col" style="display: flex;margin-left: 80px">
                        <div class="col-3">
                            <label style="margin-top:5px;" for="title">Extension: &nbsp;</label>
                        </div>
                        <div class="col">
                            <input class="form-control @error('extension')is-invalid @enderror" wire:model="extension" type="number" style="width: 70%;color:black;margin-left:50px" required>
                            <div style="color:red;margin-left:50px">@error('extension') {{ $message }} @enderror</div>
                        </div>
                    </div>

                </div>
                
                <div class="row" style="margin-top:10px">

                    <div class="col" style="display: flex;margin-left: 80px">
                        <div class="col-3">
                            <label style="margin-top:5px;" for="title">Floor: &nbsp;</label>
                        </div>
                        <div class="col">
                            <select id="floor" class="form-control" wire:model="floor" style="width: 70%;color:black;margin-left:50px" required>
                                <option value=""></option>
                                <option value="Ground Floor">Ground Floor</option>
                                <option value="Level 1">Level 1</option>
                                <option value="Level 2">Level 2</option>
                                <option value="Level 3">Level 3</option>
                                <option value="Level 4">Level 4</option>
                                <option value="Level 5">Level 5</option>
                                <option value="Level 6">Level 6</option>
                                <option value="Level 7">Level 7</option>
                              </select>
                        </div>
                    </div>

                </div>
            </div>


            <div class="modal-footer" style="align-items: center">
                <div style="margin:auto">
                    <button class="btn btn-primary">Save</button>
                    <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </form>
      </div>
    </div>
</div>
