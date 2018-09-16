# Weimeng CMS (WMCMS) ระบบจัดการเนื้อหาสำเร็จรูป
  WMCMS เป็นระบบจัดการเนื้อหาสำเร็จรูป ก่อตั้งขั้นในเดือนมกราคมปีพุทธศักราช 2555 เป็นระบบที่ตั้งอยู่บนพื้นฐานของ PHP + MySQL ที่มุ่งในการพัฒนาให้ใช้ได้ฟรีและโอเพนซอร์ส ตัวระบบเน้นไปที่การจัดการนิยายเป็นหลัก โดยตัวระบบถูกสร้างขึ้นเพื่ออำนวยความสะดวกแก่ผู้ดูแลเว็บไซต์ ซึ่งสามารถจัดการส่วนต่าง ๆ ได้อย่างง่ายดายโดยที่ไม่ต้องทำระบบด้วยตนเอง ในส่วนของนักเขียนหรือนักแปลเองก็สามารถลงผลงานด้วยตนเอง รวมไปถึงหารายได้จากการเขียนหรือแปลจากผลงานได้โดยตรงด้วยระบบ VIP ที่สามารถซื้อเป็นบท เป็นเล่ม หรือทั้งเรื่องได้

## หน้าเว็บอย่างเป็นทางการ >> [WeimengCMS](http://www.weimengcms.com)

## โมดูล
- โมดูลบทความ
- โมดูลเกี่ยวกับ
- โมดูลนิยาย
- โมดูลคุณลักษณะ
- โมดูลหน้าเดี่ยว
- โมดูลรูปภาพ
- โมดูลเว็บเพื่อนบ้าน
- โมดูลผู้ใช้
- โมดูลเว็บบอร์ด
- โมดูลแอปพลิเคชั่น
- โมดูลค้นหา

## การนำไปใช้
- เว็บบล็อกส่วนตัว
- เว็บองค์กร
- เว็บไซต์แสดงรูปภาพ
- เว็บนิยาย
- อื่น ๆ

# อัพเดทจาก V4.207.419 เป็น V4.223.430

## รายการอัพเดท
### เพิ่มใหม่ [ 16 รายการ ]
- พร็อบข้อความรางวัลแบบใหม่
- เพิ่มป้ายกำกับอันดับการขายและบันทึกรายการขาย
- เพิ่มพารามิเตอร์ค่าพื้นฐานสำหรับคลาสและป้ายกำกับ IncModule
- เพิ่มแท็บอันดับประสบการณ์แฟนคลับ
- เพิ่มแท็บอันดับรางวัลแฟนคลับ
- เพิ่มเนื้อหาบล็อกรายการรางวัลในหน้ารายละเอียดนิยาย
- เพิ่มการตรวจสอบการเชื่อมโยงบัญชีในการอ่านบทใหม่ และเชื่อมกับโมดูลผู้ใช้เพื่อดำเนินการตรวจสอบการดำเนินการที่เกี่ยวกับการซื้อ
- การตรวจสอบคลาส str อักษรดิจิตอลถูกเพิ่มเป็นว่างเปล่า
- เพิ่มการจำกัดเวลานิยายฟรีในเบื้องหลัง
- เพิ่มป้ายกำกับการจำกัดเวลานิยาย
- เพิ่มการตั้งค่ารางวัล ซึ่งคุณสามารถกำหนด อัพเดท และระยะเวลาการให้รางวัล อาทิ รางวัลจากการเช็คชื่อ รางวัลจากตัวอักษรที่พิมพ์ เป้นต้น
- เพิ่มสถิติการชำระบัญชี คุณสามารถดูรางวัลของนิยายคุณในแต่ละเดือนได้
- เพิ่มฟังก์ชั่นแอปพลิเคชั่นทางการเงิน
- เพิ่มโมเดลวิธีการดึงข้อมูลไอดีผู้ใช้โดยอิงตามไอดีผู้แต่ง
- เพิ่มฟังก์ชั่นการประมวลผลแอปพลิเคชั่นทางการเงิน
- เพิ่มเงื่อนไข เมื่อผู้ใช้มีค่าเรโชเท่ากับ 0 จะไม่สามารถเข้าดูในส่วนที่กำหนดไว้ได้

## รายการแก้ไข
### แก้ไข [ 11 รายการ ]
- แก้ไขหน้าต่างเตือนเมื่อผสานข้อมูลในโมดูลรูปภาพ
- แก้ไชไฟล์คลาสตารางโมดูลเว็บบอร์ดที่ไม่มีฟิลด์ tid
- แก้ไขเงื่อนไขการซื้อบทใหม่ในหน้าหมวดหมู่นิยาย
- แก้ไขการตรวจสอบสถานะเซสชั่นให้มีผลต่ำกว่า 5.3
- แก้ไขวิธีการกำหนดค่าโมดูลไม่ให้มีพารามิเตอร์ที่เสียหาย
- แก้ไขวิธีลงชื่อและป้ายกำกับที่ไม่ได้ลงชื่อ
- แก้ไขไอดีบทถัดไปที่ไม่มีผลในบทล่าสุด
- เพิ่มการสอบถามก่อนที่นิยายจะไปปรากฎบนชั้นหนังสือ
- แก้ไขวิธีการ returnjson ไม่ให้ส้งเครื่องหมาย +
- แก้ไขป้ายกำกับชื่อโดเมนของทั้งสี่เวอร์ชั่นที่ไม่สามารถเรียกใช้งานได้
- แก้ไขปัญหาในฟิลด์ mod

## แปลไทยโดย [SubMaRk](https://naynum.engineer)
### งานแปลนี้ไม่ตรงตามความหมาย 100% เป็นงานแปลเท่าที่สามารถแปลได้ หากพบคำผิด สามารถ Fork หรือ Clone git นี้ไปแก้ไขได้เลยครับ
