User
- id: INT, 流水號，使用者 ID
- name: VARCHAR(16) 使用者 ID ，用在網址上 https://badge.g0v.tw/{name}
- ids: JSONB 目前已經連接到哪些身份，用 array 存
- data: JSONB ，一些使用者個人的資料
  - info: 使用者資訊
  - public: 哪些服務是公開的，hash 格式
  - name: 使用者顯示名稱

Service
- id: INT, 流水號，服務 ID
- service_id: VARCHAR(32) 服務的名稱，Ex: hackpad, hackmd
- data: JSONB ，一些服務相關的資料
  - public: 服務預設是公開還是非公開（目前只有 kktix 預設非公開）
- 關聯
  - Service has_many ServiceUser through service_id
  - Service has_many ServiceBadge through service_id

ServiceUser
- id: INT, 流水號，服務使用者 ID
- service_id: INT, 服務代碼，對應到 Service 的 id
- user_id: VARCHAR(64), 服務自己本身唯一的使用者代碼，與 User 的 id 並無關係
- data: JSONB 這服務相關的內容
- 關聯
  - ServiceUser has_many ServiceBadge through service_user

ServiceBadge
- id: INT, 流水號，成就 ID
- service_id: INT, 服務代碼，對應到 Service 的 id
- service_user: INT, 服務使用者ID，對應到 ServiceUser 的 id
- badge_time: INT 成就的發生時間
- badge_hash: BIGINT 成就的名稱，用來歸類是否為同一成就
- brief: TEXT 成就的一行文字描述
- data: JSONB 成就詳細資料
