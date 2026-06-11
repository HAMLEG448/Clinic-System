<!-- ใช้ร่วมกันระหว่าง modal เพิ่ม และ modal แก้ไข -->
<div class="mb-3">
    <label class="form-label">ชื่อยา <span class="text-danger">*</span></label>
    <input type="text" name="medicine_name" class="form-control" required>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">ประเภท</label>
        <select name="medicine_type" class="form-select">
            <option value="">-- เลือกประเภท --</option>
            <option value="เม็ด">เม็ด</option>
            <option value="แคปซูล">แคปซูล</option>
            <option value="น้ำเชื่อม">น้ำเชื่อม</option>
            <option value="ครีม / ขี้ผึ้ง">ครีม / ขี้ผึ้ง</option>
            <option value="หลอด">หลอด</option>
            <option value="ยาฉีด">ยาฉีด</option>
            <option value="อื่นๆ">อื่นๆ</option>
        </select>
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">หน่วย</label>
        <input type="text" name="unit" class="form-control" placeholder="เช่น เม็ด, มล., หลอด">
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">จำนวนสต็อก</label>
        <input type="number" name="stock_qty" class="form-control" min="0" value="0">
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">ราคา/หน่วย (บาท)</label>
        <input type="number" name="price" class="form-control" min="0" step="0.01" value="0">
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">วันหมดอายุ</label>
        <input type="date" name="expiry_date" class="form-control">
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">สถานะ</label>
        <select name="status" class="form-select">
            <option value="active">ใช้งาน</option>
            <option value="inactive">ปิดใช้งาน</option>
        </select>
    </div>
</div>
