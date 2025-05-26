<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AsetResource\Pages;
use App\Filament\Resources\AsetResource\RelationManagers;
use App\Models\Aset;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AsetResource extends Resource
{
    protected static ?string $model = Aset::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('aset_category_id')
                    ->label('Kategori Aset')
                    ->relationship('kategori', 'name') // 'name' sesuai fillable dan kolom di DB
                    ->required(),
                Forms\Components\TextInput::make('kode_aset')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('nama')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('deskripsi')
                    ->columnSpanFull(),
                Forms\Components\DatePicker::make('tanggal_perolehan'),
                Forms\Components\TextInput::make('lokasi')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('nilai_perolehan')
                    ->numeric()
                    ->default(null),
                Forms\Components\TextInput::make('kondisi')
                    ->required()
                    ->maxLength(255)
                    ->default('baik'),
                Forms\Components\Toggle::make('aktif')
                    ->required(),
                FileUpload::make('foto')
                    ->label('Foto Aset')
                    ->image()
                    ->directory('aset_gambar') // simpan ke storage/app/public/aset_gambar
                    ->visibility('public')     // agar bisa ditampilkan
                    ->imagePreviewHeight('150')
                    ->maxSize(2048)
                    ->preserveFilenames()
                    ->previewable()
                    ->downloadable(),
                Forms\Components\TextInput::make('barcode')
                    ->maxLength(255)
                    ->default(null),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('aset_category_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('kode_aset')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nama')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tanggal_perolehan')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('lokasi')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nilai_perolehan')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('kondisi')
                    ->searchable(),
                Tables\Columns\IconColumn::make('aktif')
                    ->boolean(),
                Tables\Columns\ImageColumn::make('foto')
                    ->label('Foto')
                    ->disk('public') // pastikan storage:link sudah dibuat
                    ->circular()
                    ->height(50)
                    ->width(50),
                Tables\Columns\TextColumn::make('barcode')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
        ];
    }
}
