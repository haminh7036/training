<div class="col-12 col-sm-6">
    <div class="form-group">
      <label for="product_name">Tên sản phẩm</label>
      <input type="text"
        class="form-control @error('product_name') is-invalid @enderror" 
        name="product_name" id="product_name" placeholder=""
        value="{{old("product_name", "")}}">
        @error('product_name')
          <small class="form-text text-danger">{{ $message }}</small>
        @enderror
    </div>
    <div class="form-group">
        <label for="product_price">Giá bán</label>
        <input type="text"
          class="form-control @error('product_price') is-invalid @enderror" 
          name="product_price" id="product_price" placeholder=""
          value="{{old("product_price", "")}}">
          @error('product_price')
            <small class="form-text text-danger">{{ $message }}</small>
          @enderror
      </div>
      <div class="form-group">
        <label for="description">Mô tả</label>
        <textarea class="form-control" 
          name="description" id="description" rows="3">{{old("description", "")}}</textarea>
      </div>
      <div class="form-group">
        <label for="is_sales">Trạng thái</label>
        <select class="form-control @error('is_sales') is-invalid @enderror" 
          name="is_sales" id="is_sales">
          <option value="" {{old("is_sales", "") === "" ? "selected" : ""}}>Chọn</option>
          <option value="1" {{old("is_sales", "") === 1 ? "selected" : ""}}>Đang bán</option>
          <option value="0" {{old("is_sales", "") === 0 ? "selected" : ""}}>Ngừng bán</option>
          <option value="-1" {{old("is_sales", "") === -1 ? "selected" : ""}}>Hết hàng</option>
        </select>
        @error('is_sales')
            <small class="form-text text-danger">{{ $message }}</small>
        @enderror
      </div>
</div>
<div class="col-12 col-sm-6">
    <div class="form-group">
        <img id="image-demo" src="{{asset(old('product_image', 'img/no_image.png'))}}" class="img-center col">
        <div class="loading d-none" id="img-loading">
          <div class="lds-roller"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>
      </div>
    </div>
    <div class="form-group">
      <label for="">Hình ảnh</label>
        <div class="form-inline">
          <button type="button" class="btn btn-primary mb-sm-0 mr-sm-1" id="btn-upload">Chọn File</button>
          <button type="button" class="btn btn-danger mb-sm-0 mr-sm-1" id="btn-delete-file">Xóa File</button>
          <div class="form-group">
            <input type="text" class="form-control" 
            name="product_image" id="product_image" placeholder=""
            value="{{old("product_image", "")}}" readonly>
          </div>
          <input type="file" id="uploadFile" placeholder="" hidden class="custom-file-input">
        </div>
      <small id="file-error" class="text-danger d-none"></small>
      @error('product_image')
        <small class="form-text text-danger">{{ $message }}</small>
      @enderror
    </div>
    <div class="form-group float-right m-3 mb-sm-0">
        <button type="button" class="btn btn-secondary btn-goback">Huỷ</button>
        <button type="submit" class="btn btn-danger">Lưu</button>
    </div>
</div>
