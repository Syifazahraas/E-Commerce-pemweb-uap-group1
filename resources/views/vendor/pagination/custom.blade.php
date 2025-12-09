@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation">
        <ul class="pagination-custom">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="page-item-custom disabled" aria-disabled="true">
                    <span class="page-link-custom">
                        <i class="fas fa-chevron-left"></i>
                        <span class="d-none d-sm-inline ms-1">Previous</span>
                    </span>
                </li>
            @else
                <li class="page-item-custom">
                    <a class="page-link-custom" href="{{ $paginator->previousPageUrl() }}" rel="prev">
                        <i class="fas fa-chevron-left"></i>
                        <span class="d-none d-sm-inline ms-1">Previous</span>
                    </a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="page-item-custom disabled" aria-disabled="true">
                        <span class="page-link-custom">{{ $element }}</span>
                    </li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item-custom active" aria-current="page">
                                <span class="page-link-custom">{{ $page }}</span>
                            </li>
                        @else
                            <li class="page-item-custom">
                                <a class="page-link-custom" href="{{ $url }}">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="page-item-custom">
                    <a class="page-link-custom" href="{{ $paginator->nextPageUrl() }}" rel="next">
                        <span class="d-none d-sm-inline me-1">Next</span>
                        <i class="fas fa-chevron-right"></i>
                    </a>
                </li>
            @else
                <li class="page-item-custom disabled" aria-disabled="true">
                    <span class="page-link-custom">
                        <span class="d-none d-sm-inline me-1">Next</span>
                        <i class="fas fa-chevron-right"></i>
                    </span>
                </li>
            @endif
        </ul>
    </nav>

    <style>
        .pagination-custom {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
            margin: 0;
            padding: 0;
            list-style: none;
            flex-wrap: wrap;
        }

        .page-item-custom {
            margin: 0;
        }

        .page-link-custom {
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 45px;
            height: 45px;
            padding: 10px 16px;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            color: #1e3a8a;
            font-weight: 600;
            font-size: 14px;
            text-decoration: none;
            transition: all 0.3s ease;
            background: white;
            cursor: pointer;
        }

        .page-link-custom:hover {
            background: #f8fafc;
            border-color: #3b82f6;
            color: #3b82f6;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2);
        }

        .page-item-custom.active .page-link-custom {
            background: linear-gradient(135deg, #3b82f6 0%, #60a5fa 100%);
            border-color: #3b82f6;
            color: white;
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.4);
            transform: translateY(-2px);
        }

        .page-item-custom.active .page-link-custom:hover {
            background: linear-gradient(135deg, #2563eb 0%, #3b82f6 100%);
        }

        .page-item-custom.disabled .page-link-custom {
            background: #f1f5f9;
            border-color: #e2e8f0;
            color: #94a3b8;
            cursor: not-allowed;
            opacity: 0.6;
        }

        .page-item-custom.disabled .page-link-custom:hover {
            background: #f1f5f9;
            border-color: #e2e8f0;
            color: #94a3b8;
            transform: none;
            box-shadow: none;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .pagination-custom {
                gap: 5px;
            }

            .page-link-custom {
                min-width: 40px;
                height: 40px;
                padding: 8px 12px;
                font-size: 13px;
            }
        }

        @media (max-width: 576px) {
            .pagination-custom {
                gap: 4px;
            }

            .page-link-custom {
                min-width: 36px;
                height: 36px;
                padding: 6px 10px;
                font-size: 12px;
            }
        }
    </style>
@endif
