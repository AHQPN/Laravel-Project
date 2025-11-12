-- phpMyAdmin SQL Dump
-- Database: nhom2_websitexekhach
-- 
-- IMPORTANT: Import this file using MySQL command:
-- mysql -u root --default-character-set=utf8mb4 nhom2_websitexekhach < nhom2_websitexekhach_clean.sql
--
-- Or use source command in MySQL CLI:
-- mysql> source /path/to/nhom2_websitexekhach_clean.sql

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+07:00";
SET NAMES utf8mb4;

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `nhom2_websitexekhach`
--

-- --------------------------------------------------------

-- Create database if not exists
CREATE DATABASE IF NOT EXISTS `nhom2_websitexekhach` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `nhom2_websitexekhach`;

-- --------------------------------------------------------

--
-- Table structure for table `Chucvu`
--

DROP TABLE IF EXISTS `Chucvu`;
CREATE TABLE `Chucvu` (
  `macv` varchar(3) NOT NULL,
  `chucvu` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`macv`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `Chucvu`
--

INSERT INTO `Chucvu` (`macv`, `chucvu`) VALUES
('NV', 'Nhân viên bán vé'),
('PX', 'Phụ xe'),
('QL', 'Quản lý'),
('TX', 'Tài xế');

-- --------------------------------------------------------

--
-- Table structure for table `TinhThanh`
--

DROP TABLE IF EXISTS `TinhThanh`;
CREATE TABLE `TinhThanh` (
  `matinh` varchar(4) NOT NULL,
  `ten` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`matinh`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `TinhThanh`
--

INSERT INTO `TinhThanh` (`matinh`, `ten`) VALUES
('CT', 'Cần Thơ'),
('DL', 'Đà Lạt'),
('DN', 'Đà Nẵng'),
('HN', 'Hà Nội'),
('NT', 'Nha Trang'),
('SG', 'TP. Hồ Chí Minh');

-- --------------------------------------------------------

--
-- Table structure for table `Loaixe`
--

DROP TABLE IF EXISTS `Loaixe`;
CREATE TABLE `Loaixe` (
  `maloai` varchar(3) NOT NULL,
  `tenloai` varchar(100) DEFAULT NULL,
  `soghe` int(11) DEFAULT NULL,
  PRIMARY KEY (`maloai`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `Loaixe`
--

INSERT INTO `Loaixe` (`maloai`, `tenloai`, `soghe`) VALUES
('G45', 'Xe giường nằm 45 chỗ', 45),
('L22', 'Xe Limousine 22 phòng', 22),
('N40', 'Xe ghế ngồi 40 chỗ', 40);

-- --------------------------------------------------------

--
-- Table structure for table `Thanhtoan`
--

DROP TABLE IF EXISTS `Thanhtoan`;
CREATE TABLE `Thanhtoan` (
  `matt` varchar(2) NOT NULL,
  `ptthanhtoan` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`matt`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `Thanhtoan`
--

INSERT INTO `Thanhtoan` (`matt`, `ptthanhtoan`) VALUES
('CK', 'Chuyển khoản'),
('MM', 'Ví Momo'),
('TM', 'Tiền mặt'),
('VN', 'VNPAY');

-- --------------------------------------------------------

--
-- Table structure for table `Nhanvien`
--

DROP TABLE IF EXISTS `Nhanvien`;
CREATE TABLE `Nhanvien` (
  `manv` varchar(5) NOT NULL,
  `macv` varchar(3) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `ten` varchar(100) DEFAULT NULL,
  `sdt` varchar(15) DEFAULT NULL,
  `diachi` varchar(200) DEFAULT NULL,
  `cccd` varchar(12) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `ngaysinh` date DEFAULT NULL,
  `gioitinh` varchar(10) DEFAULT NULL,
  `hinhanh` varchar(255) DEFAULT NULL,
  `trangthai` tinyint(1) DEFAULT 1,
  PRIMARY KEY (`manv`),
  KEY `FK_Nhanvien_Chucvu` (`macv`),
  CONSTRAINT `FK_Nhanvien_Chucvu` FOREIGN KEY (`macv`) REFERENCES `Chucvu` (`macv`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `Nhanvien`
--

INSERT INTO `Nhanvien` (`manv`, `macv`, `password`, `ten`, `sdt`, `diachi`, `cccd`, `email`, `ngaysinh`, `gioitinh`, `hinhanh`, `trangthai`) VALUES
('NV001', 'QL', '123', 'Nguyễn Văn An', '0905123456', '123 Võ Văn Ngân, TP. Thủ Đức', '079123456789', 'an.nguyen@email.com', '1990-01-15', 'Nam', 'avatar1.jpg', 1),
('NV002', 'NV', '123', 'Trần Thị Bình', '0918765432', '456 Lê Văn Việt, Quận 9', '082123456789', 'binh.tran@email.com', '1995-05-20', 'Nữ', 'avatar2.jpg', 1),
('NV003', 'TX', '123', 'Lê Hoàng Cường', '0987654321', '789 Đỗ Xuân Hợp, Quận 2', '083123456789', 'cuong.le@email.com', '1988-11-30', 'Nam', 'avatar3.jpg', 1),
('NV004', 'PX', '123', 'Phạm Thị Dung', '0933444555', '101 Man Thiện, Quận 9', '084123456789', 'dung.pham@email.com', '1998-02-10', 'Nữ', 'avatar4.jpg', 1);

-- --------------------------------------------------------

--
-- Table structure for table `Khach`
--

DROP TABLE IF EXISTS `Khach`;
CREATE TABLE `Khach` (
  `makh` varchar(10) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `ten` varchar(100) DEFAULT NULL,
  `sdt` varchar(15) DEFAULT NULL,
  `diachi` varchar(200) DEFAULT NULL,
  `ngaysinh` date DEFAULT NULL,
  `gioitinh` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`makh`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `Khach`
--

INSERT INTO `Khach` (`makh`, `password`, `ten`, `sdt`, `diachi`, `ngaysinh`, `gioitinh`) VALUES
('KH001', '123', 'Trần Văn Bảo', '0912345678', '123 Nguyễn Huệ, Quận 1', '1999-03-12', 'Nam'),
('KH002', '123', 'Lê Thị Thu Thủy', '0987123456', '456 Đồng Khởi, Bến Nghé, Quận 1', '2001-07-25', 'Nữ'),
('KH003', '123', 'Phạm Minh Tuấn', '0977888999', '789 Hai Bà Trưng, Quận 3', '1995-11-05', 'Nam');

-- --------------------------------------------------------

--
-- Table structure for table `Xe`
--

DROP TABLE IF EXISTS `Xe`;
CREATE TABLE `Xe` (
  `maxe` varchar(5) NOT NULL,
  `maloai` varchar(3) DEFAULT NULL,
  `soxe` varchar(10) DEFAULT NULL,
  `manv` varchar(5) DEFAULT NULL,
  PRIMARY KEY (`maxe`),
  KEY `FK_Xe_Loaixe` (`maloai`),
  KEY `FK_Xe_Nhanvien` (`manv`),
  CONSTRAINT `FK_Xe_Loaixe` FOREIGN KEY (`maloai`) REFERENCES `Loaixe` (`maloai`),
  CONSTRAINT `FK_Xe_Nhanvien` FOREIGN KEY (`manv`) REFERENCES `Nhanvien` (`manv`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `Xe`
--

INSERT INTO `Xe` (`maxe`, `maloai`, `soxe`, `manv`) VALUES
('XE001', 'G45', '51A-123.45', 'NV003'),
('XE002', 'L22', '51B-678.90', 'NV003'),
('XE003', 'N40', '51C-111.22', 'NV003');

-- --------------------------------------------------------

--
-- Table structure for table `Chuyendi`
--

DROP TABLE IF EXISTS `Chuyendi`;
CREATE TABLE `Chuyendi` (
  `machuyendi` varchar(15) NOT NULL,
  `tenchuyen` varchar(100) DEFAULT NULL,
  `maxe` varchar(5) DEFAULT NULL,
  `SLgheconlai` int(11) DEFAULT NULL,
  `thoigiandi` datetime DEFAULT NULL,
  `thoigiandichuyen` int(11) DEFAULT NULL,
  `gia` int(11) DEFAULT NULL,
  PRIMARY KEY (`machuyendi`),
  KEY `FK_Chuyendi_Xe` (`maxe`),
  CONSTRAINT `FK_Chuyendi_Xe` FOREIGN KEY (`maxe`) REFERENCES `Xe` (`maxe`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `Chuyendi`
--

INSERT INTO `Chuyendi` (`machuyendi`, `tenchuyen`, `maxe`, `SLgheconlai`, `thoigiandi`, `thoigiandichuyen`, `gia`) VALUES
('HN-DN-231025C', 'Hà Nội - Đà Nẵng (Chiều)', 'XE001', 38, '2025-10-23 15:00:00', 900, 500000),
('SG-DL-221025L', 'Sài Gòn - Đà Lạt (Limousine)', 'XE002', 21, '2025-10-22 10:00:00', 360, 350000),
('SG-DN-221025A', 'Sài Gòn - Đà Nẵng (Sáng)', 'XE001', 43, '2025-10-22 08:00:00', 960, 450000);

-- --------------------------------------------------------

--
-- Table structure for table `Lotrinh`
--

DROP TABLE IF EXISTS `Lotrinh`;
CREATE TABLE `Lotrinh` (
  `machuyendi` varchar(15) NOT NULL,
  `matinh` varchar(4) NOT NULL,
  `trinhtu` int(11) DEFAULT NULL,
  PRIMARY KEY (`machuyendi`,`matinh`),
  KEY `FK_Lotrinh_TinhThanh` (`matinh`),
  CONSTRAINT `FK_Lotrinh_Chuyendi` FOREIGN KEY (`machuyendi`) REFERENCES `Chuyendi` (`machuyendi`),
  CONSTRAINT `FK_Lotrinh_TinhThanh` FOREIGN KEY (`matinh`) REFERENCES `TinhThanh` (`matinh`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `Lotrinh`
--

INSERT INTO `Lotrinh` (`machuyendi`, `matinh`, `trinhtu`) VALUES
('HN-DN-231025C', 'DN', 2),
('SG-DN-221025A', 'DN', 3),
('SG-DL-221025L', 'DL', 2),
('HN-DN-231025C', 'HN', 1),
('SG-DN-221025A', 'NT', 2),
('SG-DL-221025L', 'SG', 1),
('SG-DN-221025A', 'SG', 1);

-- --------------------------------------------------------

--
-- Table structure for table `Ve`
--

DROP TABLE IF EXISTS `Ve`;
CREATE TABLE `Ve` (
  `mave` varchar(10) NOT NULL,
  `machuyendi` varchar(15) DEFAULT NULL,
  `maghe` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`mave`),
  KEY `FK_Ve_Chuyendi` (`machuyendi`),
  CONSTRAINT `FK_Ve_Chuyendi` FOREIGN KEY (`machuyendi`) REFERENCES `Chuyendi` (`machuyendi`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `Ve`
--

INSERT INTO `Ve` (`mave`, `machuyendi`, `maghe`) VALUES
('VE00001', 'SG-DN-221025A', 'A01'),
('VE00002', 'SG-DN-221025A', 'A02'),
('VE00003', 'SG-DL-221025L', 'P05'),
('VE00004', 'HN-DN-231025C', 'B10'),
('VE00005', 'HN-DN-231025C', 'B11');

-- --------------------------------------------------------

--
-- Table structure for table `Hoadon`
--

DROP TABLE IF EXISTS `Hoadon`;
CREATE TABLE `Hoadon` (
  `mahd` varchar(10) NOT NULL,
  `thoigian` datetime DEFAULT NULL,
  `makh` varchar(10) DEFAULT NULL,
  `manv` varchar(5) DEFAULT NULL,
  `matt` varchar(2) DEFAULT NULL,
  `soluong` int(11) DEFAULT NULL,
  `thanhtien` int(11) DEFAULT NULL,
  `trangthai` varchar(20) DEFAULT 'Chờ duyệt',
  PRIMARY KEY (`mahd`),
  KEY `FK_Hoadon_Khach` (`makh`),
  KEY `FK_Hoadon_Nhanvien` (`manv`),
  KEY `FK_Hoadon_Thanhtoan` (`matt`),
  CONSTRAINT `FK_Hoadon_Khach` FOREIGN KEY (`makh`) REFERENCES `Khach` (`makh`),
  CONSTRAINT `FK_Hoadon_Nhanvien` FOREIGN KEY (`manv`) REFERENCES `Nhanvien` (`manv`),
  CONSTRAINT `FK_Hoadon_Thanhtoan` FOREIGN KEY (`matt`) REFERENCES `Thanhtoan` (`matt`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `Hoadon`
--

INSERT INTO `Hoadon` (`mahd`, `thoigian`, `makh`, `manv`, `matt`, `soluong`, `thanhtien`, `trangthai`) VALUES
('HD001', '2025-10-20 09:30:15', 'KH001', 'NV002', 'CK', 2, 900000, 'Đã duyệt'),
('HD002', '2025-10-21 10:05:00', 'KH002', 'NV002', 'MM', 1, 350000, 'Đã duyệt'),
('HD003', '2025-10-21 14:15:30', 'KH003', 'NV002', 'TM', 2, 1000000, 'Chờ duyệt');

-- --------------------------------------------------------

--
-- Table structure for table `CTHD`
--

DROP TABLE IF EXISTS `CTHD`;
CREATE TABLE `CTHD` (
  `mahd` varchar(10) NOT NULL,
  `mave` varchar(10) NOT NULL,
  `dongia` int(11) DEFAULT NULL,
  PRIMARY KEY (`mahd`,`mave`),
  KEY `FK_CTHD_Ve` (`mave`),
  CONSTRAINT `FK_CTHD_Hoadon` FOREIGN KEY (`mahd`) REFERENCES `Hoadon` (`mahd`),
  CONSTRAINT `FK_CTHD_Ve` FOREIGN KEY (`mave`) REFERENCES `Ve` (`mave`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `CTHD`
--

INSERT INTO `CTHD` (`mahd`, `mave`, `dongia`) VALUES
('HD001', 'VE00001', 450000),
('HD001', 'VE00002', 450000),
('HD002', 'VE00003', 350000),
('HD003', 'VE00004', 500000),
('HD003', 'VE00005', 500000);

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
