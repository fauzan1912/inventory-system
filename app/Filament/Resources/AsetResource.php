<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AsetResource\Pages;
use App\Filament\Resources\AsetResource\RelationManagers;
use App\Models\Aset;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Pages\SubNavigationPosition;
use Filament\Resources\Pages\Page;
use Filament\Infolists\Components;
use Filament\Infolists\Components\ImageEntry;


class AsetResource extends Resource
{
    protected static ?string $model = Aset::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Utama')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('kode_aset')
                                    ->label('Kode Aset')
                                    ->required()
                                    ->maxLength(255),

                                Forms\Components\TextInput::make('nama')
                                    ->label('Nama Aset')
                                    ->required()
                                    ->maxLength(255),

                                Forms\Components\Select::make('aset_category_id')
                                    ->label('Kategori Aset')
                                    ->relationship('kategori', 'name')
                                    ->searchable()
                                    ->required(),

                                Forms\Components\DatePicker::make('tanggal_perolehan')
                                    ->label('Tanggal Perolehan'),
                            ]),
                    ])
                    ->columns(1),

                Forms\Components\Section::make('Detail Aset')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('lokasi')
                                    ->label('Lokasi')
                                    ->maxLength(255),

                                Forms\Components\TextInput::make('nilai_perolehan')
                                    ->label('Nilai Perolehan')
                                    ->numeric()
                                    ->prefix('Rp'),

                                Forms\Components\TextInput::make('kondisi')
                                    ->label('Kondisi')
                                    ->required()
                                    ->maxLength(255),

                                Forms\Components\TextInput::make('barcode')
                                    ->label('Barcode')
                                    ->maxLength(255),
                            ]),

                        Forms\Components\Toggle::make('aktif')
                            ->label('Status Aktif')
                            ->required()
                            ->inline(false),
                    ])
                    ->columns(1)
                    ->collapsible(),

                Forms\Components\Section::make('Deskripsi Aset')
                    ->schema([
                        Forms\Components\Textarea::make('deskripsi')
                            ->label('Deskripsi')
                            ->rows(4),
                    ])
                    ->collapsible(),
                // ->collapsed(),

                Forms\Components\Section::make('Foto')
                    ->schema([
                        Forms\Components\FileUpload::make('foto')
                            ->label('Foto Aset')
                            ->image()
                            ->directory('aset_gambar')
                            ->visibility('public')
                            ->imagePreviewHeight('150')
                            ->preserveFilenames()
                            ->maxSize(2048)
                            ->previewable()
                            ->downloadable(),
                    ])
                    ->collapsible(),
                // ->collapsed(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('foto')
                    ->label('Foto')
                    ->circular()
                    ->size(40),

                Tables\Columns\TextColumn::make('kode_aset')
                    ->label('Kode')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama Aset')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('kategori.name')
                    ->label('Kategori')
                    ->sortable()
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('tanggal_perolehan')
                    ->label('Tgl. Perolehan')
                    ->date('d M Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('lokasi')
                    ->label('Lokasi')
                    ->wrap()
                    ->searchable(),

                Tables\Columns\TextColumn::make('kondisi')
                    ->label('Kondisi')
                    ->badge()
                    ->color(fn(string $state): string => match (strtolower($state)) {
                        'baik' => 'success',
                        'rusak ringan' => 'warning',
                        'rusak berat' => 'danger',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('nilai_perolehan')
                    ->label('Nilai')
                    ->money('IDR', locale: 'id_ID')
                    ->sortable(),

                Tables\Columns\IconColumn::make('aktif')
                    ->label('Aktif')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle'),
            ])
            ->filters([
                // Tambahkan filter jika diperlukan
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Components\Section::make()
                    ->schema([
                        Components\Split::make([
                            Components\Grid::make(2)
                                ->schema([
                                    Components\Group::make([
                                        TextEntry::make('kode_aset')->label('Kode Aset'),
                                        TextEntry::make('nama')->label('Nama Aset'),
                                        TextEntry::make('tanggal_perolehan')
                                            ->label('Tanggal Perolehan')
                                            ->badge()
                                            ->date()
                                            ->color('success'),
                                    ]),
                                    Components\Group::make([
                                        TextEntry::make('kategori.name')->label('Kategori'),
                                        TextEntry::make('lokasi')->label('Lokasi'),
                                        // TextEntry::make('sumberDana.nama_sumber')->label('Sumber Dana'),
                                    ]),
                                ]),
                            ImageEntry::make('foto')
                                ->hiddenLabel()
                                ->grow(false),
                        ])->from('lg'),
                    ]),

                Components\Section::make('Informasi Tambahan')
                    ->schema([
                        TextEntry::make('kondisi'),
                        // TextEntry::make('aktif')->badge()->color(fn($state) => match ($state) {
                        //     'Aktif' => 'success',
                        //     'Dimutasi' => 'warning',
                        //     'Dihapuskan' => 'danger',
                        // }),
                        TextEntry::make('nilai_perolehan')->label('Nilai Perolehan')->money('IDR'),
                    ])->columns(2)->collapsible(),
                Components\Section::make('Deskripsi Aset')
                    ->schema([
                        TextEntry::make('deskripsi')
                            ->prose()
                            ->markdown()
                            ->hiddenLabel(),
                    ])
                    ->collapsible()
                    ->collapsed(), // bisa ditampilkan collapsed default
            ]);
    }

    public static function getRecordSubNavigation(Page $page): array

    {
        return $page->generateNavigationItems([
            Pages\ViewAset::class,
            Pages\EditAset::class,
        ]);
    }


    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAsets::route('/'),
            'create' => Pages\CreateAset::route('/create'),
            'edit' => Pages\EditAset::route('/{record}/edit'),
            'view' => Pages\ViewAset::route('/{record}/view'),
        ];
    }
}
