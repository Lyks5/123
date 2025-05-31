# ER-диаграмма базы данных

```mermaid
erDiagram
    Users {
        int id PK
        string name
        string email
        string password
        string phone
        boolean is_admin
        datetime email_verified_at
        timestamp created_at
    }
    
    Products {
        int id PK
        string name
        text description
        string sku
        decimal price
        int quantity
        int category_id FK
        boolean is_featured
        int eco_score
        json sustainability_info
        decimal carbon_footprint
        timestamp created_at
    }

    Categories {
        int id PK
        string name
        string slug
        text description
        int parent_id FK
    }

    Orders {
        int id PK
        int user_id FK
        string status
        decimal total_amount
        decimal tax_amount
        decimal shipping_amount
        int shipping_address_id FK
        int billing_address_id FK
        string payment_method
        string shipping_method
        text notes
        boolean carbon_offset
        string tracking_number
        timestamp created_at
    }

    EcoFeatures {
        int id PK
        string name
        string slug
        text description
        string icon
        boolean is_active
    }

    Addresses {
        int id PK
        int user_id FK
        string street
        string city
        string country
        string postal_code
        string phone
    }

    Reviews {
        int id PK
        int user_id FK
        int product_id FK
        int rating
        text comment
        timestamp created_at
    }

    Carts {
        int id PK
        int user_id FK
        timestamp created_at
    }

    CartItems {
        int id PK
        int cart_id FK
        int product_id FK
        int variant_id FK
        int quantity
    }

    Wishlists {
        int id PK
        int user_id FK
        int product_id FK
        timestamp created_at
    }

    ProductImages {
        int id PK
        int product_id FK
        string url
        boolean is_primary
        int sort_order
    }

    Users ||--o{ Orders : "размещает"
    Users ||--o{ Reviews : "оставляет"
    Users ||--o{ Addresses : "имеет"
    Users ||--o{ Carts : "имеет"
    Users ||--o{ Wishlists : "создает"
    
    Products ||--o{ Reviews : "имеет"
    Products ||--o{ ProductImages : "имеет"
    Products ||--o{ CartItems : "содержится в"
    Products ||--o{ Wishlists : "добавлен в"
    Products }o--|| Categories : "принадлежит"
    
    Products }|--|{ EcoFeatures : "имеет"
    
    Orders ||--|{ CartItems : "содержит"
    Orders }|--|| Addresses : "адрес доставки"
    Orders }|--|| Addresses : "адрес оплаты"
    
    Carts ||--|{ CartItems : "содержит"