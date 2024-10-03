<div class="col-md-2">
        <label for="code">Discount</label>
        <div class="form-group">
            <input type="number" step="any" name="discount" class="form-control" value="{{$customerExtend->discount ?? ''  }}" placeholder="Discount">
        </div>
    </div>
    <div class="col-md-3">
        <label for="code">Special Discounts</label>
        <div class="form-group">
            <input type="number" step="any" name="special_discount" class="form-control" value="{{$customerExtend->special_discount ?? ''  }}" placeholder="Special Discount">
        </div>
    </div>
    <div class="col-md-3">
        <label for="code">Advance Payment</label>
        <div class="form-group">
            <input type="number" step="any" name="adv_payment" class="form-control" value="{{$customerExtend->adv_payment ?? ''  }}" placeholder="Advance Payment">
        </div>
    </div>
    <div class="col-md-2">
        <label for="code">COD</label>
        <div class="form-group">
            <input type="number" step="any" name="cod" class="form-control" value="{{ $customerExtend->cod ?? ''  }}" placeholder="C-O-D">
        </div>
    </div>
    <div class="col-md-3">
        <label for="code">CNIC</label>
        <div class="form-group">
            <input type="text" name="cnic" class="form-control" value="{{ $customerExtend->cnic ?? ''  }}"  placeholder="CNIC">
        </div>
    </div>
    <div class="col-md-3">
        <label for="code">Chanell</label>
        <div class="form-group">
            <input type="text" name="chanell" class="form-control" value="{{$customerExtend->chanell ?? ''  }}" placeholder="Chanell">
        </div>
    </div>
    <div class="col-md-3">
        <label for="code">S.T.N</label>
        <div class="form-group">
            <input type="text" name="stn" class="form-control" value="{{$customerExtend->stn ?? ''  }}" placeholder="S.T.N">
        </div>
    </div>
    
