# Onix-diploma (internet shop)

## End-points

### Users
- GET /users [admin]
- GET /users/me [user{own}]
- PUT /users/me [user{own}]
- PUT /users/{user} [admin]
- DELETE /users/{user} [admin]
### Products
- GET /products?category_ids=1,2,3&sort_by=rating [user, admin]
- POST /products [admin]
- GET /products/{id} [user, admin]
- PUT /products/{id} [admin]
- DELETE /products/{id} [admin]
- GET /products/{id}/questions  
[{“question”: [“answer1", “answer2]}]
### Categories
- GET /categories [user, admin]
- POST /categories [admin]
- GET /categories/{id} [user, admin]
- PUT /categories/{id} [admin]
- DELETE /categories/{id} [admin]
### Orders
- GET /orders [user{own}, admin{all}]
- POST /orders [user]
- GET /orders/{id} [user{own}, admin]
- PUT /orders/{id} [user{own}, admin]
- DELETE /orders/{id} [user{own}, admin]
### Reviews
- GET /previews?product_id=1 [user, admin]
- POST /reviews [user]
- GET /reviews/{id} [user, admin]
- PUT /reviews/{id} [user{own}, admin]
- DELETE /reviews/{id} [user{own}, admin]
### Cart
- POST /cart [user{own}]
- GET /cart [user{own}]
- DELETE /cart [user{own}] видалити все
- DELETE /cart/{cart} [user{own}] видалити один елемент
### Questions
- GET /questions?product_id=1
- POST /questions
- GET /questions/{id}
- PUT /questions/{id}
- DELETE /questions/{id}
- POST /questions/{id}/answer
- PUT /answers/{answer}
- DELETE /answers/{answer}
