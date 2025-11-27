@extends('layouts.khach')

@section('content')
    <style>
        .lookup-container {
            background: #f8f9fa;
            min-height: 100vh;
            padding: 3rem 0;
        }

        .lookup-box {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .lookup-header {
            background: linear-gradient(135deg, #f97019 0%, #e5640f 100%);
            padding: 2rem;
            text-align: center;
            color: white;
        }

        .lookup-header i {
            font-size: 3rem;
            margin-bottom: 1rem;
        }

        .lookup-header h2 {
            margin: 0;
            font-size: 1.75rem;
            font-weight: 700;
        }

        .lookup-header p {
            margin: 0.5rem 0 0 0;
            opacity: 0.9;
            font-size: 0.95rem;
        }

        .lookup-body {
            padding: 2.5rem 2rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            font-weight: 600;
            color: #495057;
            margin-bottom: 0.75rem;
            font-size: 0.95rem;
        }

        .form-input {
            width: 100%;
            padding: 0.85rem 1rem;
            border: 2px solid #dee2e6;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s;
        }

        .form-input:focus {
            border-color: #f97019;
            outline: none;
            box-shadow: 0 0 0 0.2rem rgba(249, 112, 25, 0.15);
        }

        .btn-lookup {
            width: 100%;
            background: #f97019;
            color: white;
            border: none;
            padding: 0.95rem;
            border-radius: 8px;
            font-weight: 700;
            font-size: 1.05rem;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-lookup:hover {
            background: #e5640f;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(249, 112, 25, 0.3);
        }

        .info-box {
            background: #e7f3ff;
            border-left: 4px solid #0d6efd;
            padding: 1rem 1.25rem;
            margin-top: 1.5rem;
            border-radius: 5px;
        }

        .info-box i {
            color: #0d6efd;
            margin-right: 0.5rem;
        }

        .info-box p {
            margin: 0;
            color: #0c5aa6;
            font-size: 0.9rem;
        }

        .quick-links {
            text-align: center;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid #dee2e6;
        }

        .quick-links a {
            color: #f97019;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .quick-links a:hover {
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .lookup-container {
                padding: 2rem 1rem;
            }

            .lookup-body {
                padding: 1.5rem;
            }
        }
    </style>

    <div class="lookup-container">
        <div class="container">
            <div class="lookup-box">
                <!-- Header -->
                <div class="lookup-header">
                    <i class="bi bi-ticket-perforated"></i>
                    <h2>Tra cứu vé</h2>
                    <p>Nhập mã vé để tra cứu thông tin chi tiết</p>
                </div>

                <!-- Body -->
                <div class="lookup-body">
                    <form action="{{ route('ticket.lookup') }}" method="POST">
                        @csrf

                        <div class="form-group">
                            <label class="form-label">
                                <i class="bi bi-upc-scan me-1"></i>
                                Mã vé
                            </label>
                            <input type="text" name="mave" class="form-input @error('mave') is-invalid @enderror"
                                placeholder="Nhập mã vé (VD: VE12345678)" value="{{ old('mave') }}" required autofocus>
                            @error('mave')
                                <div class="text-danger mt-2" style="font-size: 0.875rem;">
                                    <i class="bi bi-exclamation-circle me-1"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <button type="submit" class="btn-lookup">
                            <i class="bi bi-search me-2"></i>
                            Tra cứu vé
                        </button>
                    </form>

                    <!-- Info Box -->
                    <div class="info-box">
                        <i class="bi bi-info-circle-fill"></i>
                        <p>Mã vé được cung cấp trong email xác nhận hoặc trên hóa đơn của bạn.</p>
                    </div>

                    <!-- Quick Links -->
                    <div class="quick-links">
                        <a href="{{ route('bill.index') }}">
                            <i class="bi bi-clock-history me-1"></i>
                            Xem lịch sử mua vé
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection